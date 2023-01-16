<?php

namespace Flogar\Sunat\GRE\Api;

use Flogar\Sunat\GRE\Model\CpeDocument;
use Flogar\Sunat\GRE\Model\CpeResponse;
use Flogar\Sunat\GRE\Model\StatusResponse;

interface CpeApiInterface
{
    /**
     * Permite realizar el envio del comprobante.
     *
     * @param string $filename
     * @param CpeDocument $cpe_document
     * @return CpeResponse
     */
    public function enviarCpe(string $filename, CpeDocument $cpe_document): CpeResponse;

    /**
     * Permite realizar la consulta del envío realizado.
     *
     * @param string $ticket
     * @return StatusResponse
     */
    public function consultarEnvio(string $ticket): StatusResponse;
}