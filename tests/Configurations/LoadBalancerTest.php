<?php

namespace Cloudflare\API\Test\Configurations;

use Cloudflare\API\Configurations\ConfigurationsException;
use Cloudflare\API\Configurations\LoadBalancer;
use Cloudflare\API\Test\TestCase;

/**
 * @author Martijn Smidt <martijn@squeezely.tech>
 * User: HemeraOne
 * Date: 13/05/2019
 */
class LoadBalancerTest extends TestCase
{
    /**
     * @dataProvider argumentsDataProvider
     */
    public function testArguments($setFunction, $arguments, $getFunction, $invalid)
    {
        $loadBalancer = new LoadBalancer('bogus', [], 'bogus');
        foreach ($arguments as $argument) {
            if ($invalid === true) {
                try {
                    $loadBalancer->{$setFunction}($argument);
                } catch (ConfigurationsException $exception) {
                    $this->assertNotEquals($argument, $loadBalancer->{$getFunction}());
                }
            } elseif ($invalid === false) {
                $loadBalancer->{$setFunction}($argument);
                $this->assertEquals($argument, $loadBalancer->{$getFunction}());
            }
        }
    }

    public function argumentsDataProvider()
    {
        return [
            'steeringPolicy arguments valid' => [
                'setSteeringPolicy', ['off', 'geo', 'random', 'dynamic_latency', ''], 'getSteeringPolicy', false
            ],
            'sessionAffinity arguments valid' => [
                'setSessionAffinity', ['none', 'cookie', 'ip_cookie', ''], 'getSessionAffinity', false
            ],
            'sessionAffinityTtl arguments valid' => [
                'setSessionAffinityTtl', [3600], 'getSessionAffinityTtl', false
            ],
            'steeringPolicy arguments invalid' => [
                'setSteeringPolicy', ['invalid'], 'getSteeringPolicy', true
            ],
            'sessionAffinity arguments invalid' => [
                'setSessionAffinity', ['invalid'], 'getSessionAffinity', true
            ],
            'sessionAffinityTtl arguments invalid' => [
                'setSessionAffinityTtl', [1337], 'getSessionAffinityTtl', true
            ],
        ];
    }
}
