<?php

/**
 * This file is part of Nepttune (https://www.peldax.com)
 *
 * Copyright (c) 2018 Václav Pelíšek (info@peldax.com)
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <https://www.peldax.com>.
 */

declare(strict_types = 1);

namespace Nepttune\Model;

class GoogleGeocodeModel
{
    use \Nette\SmartObject;

    protected const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json?';

    /** @var array */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function geocodeAddress(string $address) : \stdClass
    {
        return $this->request(['address' => $address]);
    }

    public function geocodeLatLng(array $latLng) : \stdClass
    {
        return $this->request(['location' => \implode(',', $latLng)]);
    }

    public function geocodeGoogle(string $googleId) : \stdClass
    {
        return $this->request(['place_id' => $googleId]);
    }

    protected function request(array $params) : \stdClass
    {
        $params['key'] = $this->config['key'];
        $response = \file_get_contents(static::API_URL . \http_build_query($params));
        $json = \json_decode($response);

        if ($json->status !== 'OK')
        {
            return null;
        }

        return $json->results[0];
    }
}
