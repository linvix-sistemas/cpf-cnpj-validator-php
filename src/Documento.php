<?php

namespace LinvixSistemas\ValidadorCpfCnpj;

class Documento extends DocumentoAbstract
{
    /**
     * Value to be validated
     *
     * @var DocumentoAbstract
     */
    public $obj;

    /**
     * Get document type
     */
    public function getType(): String
    {
        return $this->obj->getClassName();
    }

    /**
     * Check if it is a valid number
     *
     * @return bool|string
     */
    public function isValid()
    {
        return $this->obj->isValid();
    }

    /**
     * Format number
     *
     * @return string
     */
    public function format()
    {
        return $this->obj->format();
    }

    /**
     * Get the raw value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->obj->getValue();
    }

    /**
     * Set the clean value.
     * Strips only mask characters (. / -) before deciding CPF (11 chars) vs
     * CNPJ (14 chars, may contain letters in the new alphanumeric format).
     *
     * @return self
     */
    public function setValue(string $value)
    {
        // Remove mask chars only, keep letters so alphanumeric CNPJ length is preserved
        $cleaned = strtoupper((string) preg_replace('/[.\/-]/', '', $value));

        if (strlen($cleaned) === 11) {
            // CPF is always numeric; strip any remaining non-digit chars
            $this->obj = new CPF(preg_replace('/[^0-9]/', '', $value));
        } else {
            $this->obj = new CNPJ($cleaned);
        }

        return $this;
    }
}
