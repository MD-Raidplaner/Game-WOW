<?php

namespace rp\system\item\database;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use wcf\data\language\Language;
use wcf\system\io\HttpFactory;
use wcf\util\StringUtil;
use wcf\util\XML;

/**
 * WOW:BNet Armory implementation for item databases.
 * 
 * @author  Marco Daries
 * @copyright   2025 MD-Raidplaner
 * @license MD-Raidplaner is licensed under Creative Commons Attribution-ShareAlike 4.0 International
 */
final class WOWHeadItemDatabase implements IItemDatabase
{
    /**
     * The HTTP client instance used for making requests.
     */
    private ClientInterface $httpClient;

    /**
     * Creates and configures an HTTP client with a timeout setting of 30 seconds.
     */
    private function getHttpClient(): ClientInterface
    {
        if (!isset($this->httpClient)) {
            $this->httpClient = HttpFactory::makeClientWithTimeout(10);
        }

        return $this->httpClient;
    }

    /**
     * @inheritDoc
     */
    public function getItemData(string|int $itemID, ?Language $language = null, ?string $additionalData = null): ?array
    {
        if (empty($itemID) || !$itemID) {
            return null;
        }

        $url = \sprintf(
            'https://%s.wowhead.com/item=%s&xml&bonus=%s',
            $language->languageCode === 'en' ? 'www' : $language->languageCode,
            $itemID,
            $additionalData
        );

        $request = new Request('GET', $url, [
            'accept' => 'application/xml',
        ]);

        try {
            $response = $this->getHttpClient()->send($request);
            $xmlData = $response->getBody();
        } catch (TransferException $e) {
            throw $e;
        }

        $reader = new \XMLReader();
        $reader->xml($xmlData);

        $itemData = [];
        while ($reader->read()) {
            if (
                $reader->nodeType == \XMLReader::ELEMENT &&
                $reader->localName == 'item'
            ) {
                $itemXML = $reader->readOuterXml();
                $item = new \SimpleXMLElement($itemXML);

                foreach ($item->children() as $child) {
                    $tagName = $child->getName();

                    if ($child->attributes()) {
                        $itemData[$tagName] = [
                            'value' => (string)$child
                        ];

                        foreach ($child->attributes() as $attrName => $attrValue) {
                            if ($attrName === 'id') {
                                $itemData[$tagName][$attrName] = (int)$attrValue;
                            } else {
                                $itemData[$tagName][$attrName] = (string)$attrValue;
                            }
                        }
                    } else {
                        $itemData[$tagName] = (string)$child;
                    }
                }
            }
        }

        $reader->close();

        if (!empty($itemData)) {
            $itemData['icon'] = $itemData['icon']['value'] ?? 'inv_misc_questionmark';
            $itemData['iconExtension'] = 'jpg';
            $itemData['iconURL'] = 'https://wow.zamimg.com/images/wow/icons/large/';
            $itemData['tooltip'] = $itemData['htmlTooltip'] ?? '';
        }

        return $itemData;
    }

    /**
     * @inheritDoc
     */
    public function searchItemID(string $itemName, ?Language $language = null): int|string
    {
        $itemID = 0;

        $url = \sprintf(
            'https://%s.wowhead.com/item=%s&xml',
            $language->languageCode === 'en' ? 'www' : $language->languageCode,
            $itemName
        );

        $request = new Request('GET', $url, [
            'accept' => 'application/xml',
        ]);

        try {
            $response = $this->getHttpClient()->send($request);
            $xmlData = $response->getBody();
        } catch (TransferException $e) {
            throw $e;
        }

        $reader = new \XMLReader();
        $reader->xml($xmlData);
        while ($reader->read()) {
            if (
                $reader->nodeType == \XMLReader::ELEMENT &&
                $reader->localName == 'item'
            ) {
                $itemID = (int)$reader->getAttribute('id');
                $reader->close();
                break;
            }
        }

        if (!$itemID) {
            $url = \sprintf(
                'https://%s.wowhead.com/search?q=%s',
                $language->languageCode === 'en' ? 'www' : $language->languageCode,
                $encodedName
            );

            $request = new Request('GET', $url, [
                'accept' => 'text/html',
            ]);

            try {
                $response = $this->getHttpClient()->send($request);
                $searchData = (string)$response->getBody();
            } catch (TransferException $e) {
                throw $e;
            }

            $pattern = \sprintf(
                '/"id":(\d+),"level":(\d+),"name":"%s"/',
                \preg_quote($itemName, '/')
            );

            if (\preg_match_all($pattern, $searchData, $matches)) {
                $itemID = !empty($matches[1]) ? (int)\array_unique($matches[1])[0] : 0;
            }
        }

        return $itemID;
    }
}
