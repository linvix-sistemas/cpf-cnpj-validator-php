<?php

namespace LinvixSistemas\ValidadorCpfCnpj;

class CNPJ extends DocumentoAbstract
{
    /**
     * Default block list
     *
     * @var array
     */
    protected const BLOCKLIST = [
        '00000000000000',
    ];

    /**
     * Weights for DV calculation (13 positions, used with offset 0 or 1)
     */
    private const DV_WEIGHTS = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    /**
     * ASCII ordinal of '0', used to convert alphanumeric chars to numeric value
     */
    private const ASCII_ZERO = 48;

    /**
     * Set the clean value, stripping mask chars and normalising to uppercase.
     * Supports the new alphanumeric CNPJ format (A-Z and 0-9 are kept).
     *
     * @return self
     */
    public function setValue(string $value)
    {
        $this->value = strtoupper((string) preg_replace('/[^A-Z0-9]/i', '', $value));
        return $this;
    }

    /**
     * Check if it is a valid CNPJ number.
     * Supports both the classic all-numeric format and the new alphanumeric
     * format (Receita Federal): 12 alphanumeric base chars + 2 numeric DV digits.
     *
     * @return bool
     */
    public function isValid()
    {
        if (strlen($this->value) !== 14) {
            return false;
        }

        // 12 alphanumeric chars (A-Z, 0-9) followed by exactly 2 digit DV
        if (!preg_match('/^[A-Z0-9]{12}[0-9]{2}$/', $this->value)) {
            return false;
        }

        if (in_array($this->value, self::BLOCKLIST, true)) {
            return false;
        }

        $calculated = $this->calculateDV(substr($this->value, 0, 12));
        $informed    = substr($this->value, 12, 2);

        return $calculated === $informed;
    }

    /**
     * Format CNPJ as ##.###.###/####-##
     *
     * @return string|bool
     */
    public function format()
    {
        if (!$this->isValid()) {
            return false;
        }

        return substr($this->value, 0, 2) . '.'
            . substr($this->value, 2, 3) . '.'
            . substr($this->value, 5, 3) . '/'
            . substr($this->value, 8, 4) . '-'
            . substr($this->value, 12, 2);
    }

    /**
     * Calculate the two check digits for a 12-character CNPJ base.
     *
     * Each character is converted to its numeric value via
     * ord($char) - ord('0'), which maps '0'→0 … '9'→9, 'A'→17, 'B'→18 …
     * This is identical to the legacy behaviour for pure-digit CNPJs.
     *
     * @param string $base 12-character base string (no DV)
     * @return string Two-digit DV string, e.g. "01"
     */
    private function calculateDV(string $base): string
    {
        $sumDV1 = 0;
        $sumDV2 = 0;

        for ($i = 0; $i < 12; $i++) {
            $val     = ord($base[$i]) - self::ASCII_ZERO;
            $sumDV1 += $val * self::DV_WEIGHTS[$i + 1];
            $sumDV2 += $val * self::DV_WEIGHTS[$i];
        }

        $dv1     = $sumDV1 % 11 < 2 ? 0 : 11 - ($sumDV1 % 11);
        $sumDV2 += $dv1 * self::DV_WEIGHTS[12];
        $dv2     = $sumDV2 % 11 < 2 ? 0 : 11 - ($sumDV2 % 11);

        return "{$dv1}{$dv2}";
    }
}
