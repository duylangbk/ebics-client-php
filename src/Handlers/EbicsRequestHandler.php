<?php

namespace AndrewSvirin\Ebics\Handlers;

use DOMDocument;
use DOMElement;

/**
 * Class RequestHandler manage request DOM elements.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class EbicsRequestHandler
{
    const EBICS_REQUEST = 'ebicsRequest';
    const EBICS_UNSECURED_REQUEST = 'ebicsUnsecuredRequest';
    const EBICS_NO_PUB_KEY_DIGESTS = 'ebicsNoPubKeyDigestsRequest';
    const EBICS_HEV = 'ebicsHEVRequest';

    /**
     * Add SecuredRequest to DOM XML.
     */
    public function handleSecured(DOMDocument $xml): DOMElement
    {
        return $this->handleH004Secured($xml, self::EBICS_REQUEST);
    }

    /**
     * Add UnsecuredRequest to DOM XML.
     */
    public function handleUnsecured(DOMDocument $xml): DOMElement
    {
        return $this->handleH004($xml, self::EBICS_UNSECURED_REQUEST);
    }

    /**
     * Add NoPubKeyDigestsRequest to DOM XML.
     */
    public function handleNoPubKeyDigests(DOMDocument $xml): DOMElement
    {
        return $this->handleH004Secured($xml, self::EBICS_NO_PUB_KEY_DIGESTS);
    }

    /**
     * Add HEV Request to DOM XML.
     *
     * @return DOMElement
     */
    public function handleHEV(DOMDocument $xml)
    {
        return $this->handleH000($xml, self::EBICS_HEV);
    }

    /**
     * Add H004 Request to DOM XML.
     *
     * @param string $request
     */
    private function handleH004(DOMDocument $xml, $request): DOMElement
    {
        $xmlRequest = $xml->createElementNS('urn:org:ebics:H004', $request);
        $xmlRequest->setAttribute('Version', 'H004');
        $xmlRequest->setAttribute('Revision', '1');
        $xml->appendChild($xmlRequest);

        return $xmlRequest;
    }

    /**
     * Add H004 Request with ds:xmlns for sign to DOM XML.
     *
     * @param string $request
     */
    private function handleH004Secured(DOMDocument $xml, $request): DOMElement
    {
        $xmlRequest = $xml->createElementNS('urn:org:ebics:H004', $request);
        $xmlRequest->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
        $xmlRequest->setAttribute('Version', 'H004');
        $xmlRequest->setAttribute('Revision', '1');
        $xml->appendChild($xmlRequest);

        return $xmlRequest;
    }

    /**
     * Add H000 Request to DOM XML.
     *
     * @param string $request
     */
    private function handleH000(DOMDocument $xml, $request): DOMElement
    {
        $xmlRequest = $xml->createElementNS('http://www.ebics.org/H000', $request);
        $xmlRequest->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation', 'http://www.ebics.org/H000 http://www.ebics.org/H000/ebics_hev.xsd');
        $xml->appendChild($xmlRequest);

        return $xmlRequest;
    }
}
