<?php
namespace MathPHP;

class FinanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForcheckZero
     */
    public function testcheckZero(float $value, float $result)
    {
        $reflection = new \ReflectionClass('MathPHP\Finance');
        $method = $reflection->getMethod('checkZero');
        $method->setAccessible(true);
        $this->assertEquals($result, $method->invokeArgs(null, [$value]));
    }

    public function dataProviderForcheckZero()
    {
        return [
            [0.0, 0.0],
            [0.1, 0.1],
            [0.01, 0.01],
            [0.001, 0.001],
            [0.0001, 0.0001],
            [0.00001, 0.00001],
            [0.000001, 0.000001],
            [0.0000001, 0.0000001],
            [0.00000001, 0.00000001],
            [0.000000001, 0.000000001],
            [0.0000000001, 0.0],
            [Finance::EPSILON, Finance::EPSILON],
            [Finance::EPSILON / 2, 0.0],
            [1.0, 1.0],
            [10.0, 10.0],
            [1e8, 1e8],
            [1e9, 1e9],
        ];
    }

    /**
     * @dataProvider dataProviderForPMT
     */
    public function testPMT(float $rate, int $periods, float $pv, float $fv, bool $beginning, float $pmt)
    {
        $this->assertEquals($pmt, Finance::pmt($rate, $periods, $pv, $fv, $beginning));
    }

    public function dataProviderForPMT()
    {
        return [
            [0.0, 1, 0, 0, false, 0.0],
            [0.0, 1, 1, 0, false, -1.0],
            [0.0, 1, -1, 0, false, 1.0],
            [0.0, 1, 1, 0, true, -1.0],
            [0.0, 1, -1, 0, true, 1.0],
            [0.0, 2, 1, 0, false, -0.5],
            [0.0, 2, -1, 0, false, 0.5],
            [0.0, 2, 1, 0, true, -0.5],
            [0.0, 2, -1, 0, true, 0.5],
            [0.05, 30, 250000, 0, false, -16262.858770069148],
            [0.05, 30, -250000, 0, false, 16262.858770069148],
            [0.05, 30, 250000, 0, true, -15488.436923875368],
            [0.05, 30, -250000, 0, true, 15488.436923875368],
            [0.04/12, 12*30, 85000, 0, false, -405.80300114563494],
            [0.04/12, 12*30, -85000, 0, false, 405.80300114563494],
            [0.04/12, 12*30, 85000, 0, true, -404.45481841757629],
            [0.04/12, 12*30, -85000, 0, true, 404.45481841757629],
            [0.035/12, 12*30, 475000, 0, false, -2132.9622670919189],
            [0.035/12, 12*30, -475000, 0, false, 2132.9622670919189],
            [0.035/12, 12*30, 475000, 0, true, -2126.7592193687524],
            [0.035/12, 12*30, -475000, 0, true, 2126.7592193687524],
            [0.035/12, 12*30, 475000, 100000, false, -2290.3402882340679],
            [0.035/12, 12*30, -475000, -100000, false, 2290.3402882340679],
            [0.035/12, 12*30, 475000, 100000, true, -2283.6795561951658],
            [0.035/12, 12*30, -475000, -100000, true, 2283.6795561951658],
            [0.10/4, 5*4, 0, 50000, false, -1957.3564367237279],
            [0.10/4, 5*4, 0, -50000, false, 1957.3564367237279],
            [0.10/4, 5*4, 0, 50000, true, -1909.6160358280276],
            [0.10/4, 5*4, 0, -50000, true, 1909.6160358280276],
            [0.035/12, 30*12, 265000, 0, false, -1189.9684226933862],
            [0.035/12, 5*12, 265000, 265000/2, false, -6844.7602923435943],
            [0.01/52, 3*52, -1500, 10000, false, -53.390735324685636],
            [0.04/4, 20*4, 1000000, 0, false, -18218.850112732187],
        ];
    }

    /**
     * @dataProvider dataProviderForAER
     */
    public function testAER(float$nominal, int $periods, float $rate)
    {
        $this->assertEquals($rate, Finance::aer($nominal, $periods));
    }

    public function dataProviderForAER()
    {
        return [
            [0.0, 1, 0.0],
            [0.035, 12, 0.035566952945970565],
            [0.06, 12, 0.061677811864497611],
            [0.01, 1, 0.01],
            [0.01, 2, 0.010024999999999729],
            [0.01, 4, 0.010037562539062295],
            [0.01, 12, 0.010045960887180572],
            [0.01, 365, 0.010050028723672],
            [0.05, 1, 0.05],
            [0.05, 2, 0.05062499999999992],
            [0.05, 4, 0.050945336914062445],
            [0.05, 12, 0.05116189788173342],
            [0.05, 365, 0.051267496467422902],
            [0.10, 1, 0.1],
            [0.10, 2, 0.1025],
            [0.10, 4, 0.10381289062499977],
            [0.10, 12, 0.10471306744129683],
            [0.10, 365, 0.10515578161622718],
            [0.15, 1, 0.15],
            [0.15, 2, 0.1556249999999999],
            [0.15, 4, 0.15865041503906308],
            [0.15, 12, 0.16075451772299854],
            [0.15, 365, 0.16179844312826397],
            [0.20, 1, 0.2],
            [0.20, 2, 0.21000000000000019],
            [0.20, 4, 0.21550625000000001],
            [0.20, 12, 0.21939108490523185],
            [0.20, 365, 0.22133585825175062],
            [0.30, 1, 0.3],
            [0.30, 2, 0.32249999999999979],
            [0.30, 4, 0.33546914062499988],
            [0.30, 12, 0.34488882424629752],
            [0.30, 365, 0.34969248800768127],
            [0.40, 1, 0.4],
            [0.40, 2, 0.43999999999999995],
            [0.40, 4, 0.4641],
            [0.40, 12, 0.48212648965463845],
            [0.40, 365, 0.49149799683290096],
            [0.50, 1, 0.50],
            [0.50, 2, 0.5625],
            [0.50, 4, 0.601806640625],
            [0.50, 12, 0.63209413272292592],
            [0.50, 365, 0.64815725173913452],
            [1.0, 1, 1.0],
            [1.0, 2, 1.25],
        ];
    }

    /**
     * @dataProvider dataProviderForFV
     */
    public function testFV(float $rate, int $periods, float $pmt, float $pv, bool $beginning, float $fv)
    {
        $this->assertEquals($fv, Finance::fv($rate, $periods, $pmt, $pv, $beginning));
    }

    public function dataProviderForFV()
    {
        return [
            [0.0, 0, 0, 0, false, 0.0],
            [0.1, 0, 0, 0, false, 0.0],
            [0.0, 1, 0, 0, false, 0.0],
            [0.0, 0, 1, 0, false, 0.0],
            [0.0, 0, 0, 1, false, -1.0],
            [0.0, 0, 0, -1, false, 1.0],
            [0.0, 0, 1, 1, false, -1.0],
            [0.0, 0, -1, -1, false, 1.0],
            [0.0, 0, -1, 1, false, -1.0],
            [0.0, 0, 1, -1, false, 1.0],
            [0.0, 1, 1, 1, false, -2.0],
            [0.0, 1, -1, 1, false, 0.0],
            [0.0, 1, 1, -1, false, 0.0],
            [0.0, 1, -1, -1, false, 2.0],
            [0.1, 0, 0, 0, false, 0.0],
            [0.1, 1, 0, 0, false, 0.0],
            [0.1, 0, 1, 0, false, 0.0],
            [0.1, 0, 0, 1, false, -1.0],
            [0.1, 1, 1, 0, false, -1.0],
            [0.1, 1, 0, 1, false, -1.1],
            [0.1, 1, 1, 1, false, -2.1],
            [0.0, 0, 0, 0, true, 0.0],
            [0.1, 0, 0, 0, true, 0.0],
            [0.0, 1, 0, 0, true, 0.0],
            [0.0, 0, 1, 0, true, 0.0],
            [0.0, 0, 0, 1, true, -1.0],
            [0.0, 0, 0, -1, true, 1.0],
            [0.0, 0, 1, 1, true, -1.0],
            [0.0, 0, -1, -1, true, 1.0],
            [0.0, 0, -1, 1, true, -1.0],
            [0.0, 0, 1, -1, true, 1.0],
            [0.0, 1, 1, 1, true, -2.0],
            [0.0, 1, -1, 1, true, 0.0],
            [0.0, 1, 1, -1, true, 0.0],
            [0.0, 1, -1, -1, true, 2.0],
            [0.1, 0, 0, 0, true, 0.0],
            [0.1, 1, 0, 0, true, 0.0],
            [0.1, 0, 1, 0, true, 0.0],
            [0.1, 0, 0, 1, true, -1.0],
            [0.1, 1, 1, 0, true, -1.1],
            [0.1, 1, 0, 1, true, -1.1],
            [0.1, 1, 1, 1, true, -2.2],
            [0.05/12, 120, -100, -100, false, 15692.928894335892],
            [0.035/12, 360, 2132.9622670919189, 475000, false, -2710622.8069359586],
            [0.035/12, 360, -2132.9622670919189, 475000, false, 0.0],
            [0.035/12, 360, 2132.9622670919189, -475000, false, 0.0],
            [0.035/12, 360, -2132.9622670919189, -475000, false, 2710622.8069359586],
            [0.035/12, 360, 2132.9622670919189, 475000, true, -2714575.798529407],
            [0.035/12, 360, -2132.9622670919189, 475000, true, 3952.9915934484452],
            [0.035/12, 360, 2132.9622670919189, -475000, true, -3952.9915934484452],
            [0.035/12, 360, -2132.9622670919189, -475000, true, 2714575.798529407],
        ];
    }

    /**
     * @dataProvider dataProviderForPV
     */
    public function testPV(float $rate, int $periods, float $pmt, float $fv, bool $beginning, float $pv)
    {
        $this->assertEquals($pv, Finance::pv($rate, $periods, $pmt, $fv, $beginning));
    }

    public function dataProviderForPV()
    {
        return [
            [0.0, 0, 0, 0, false, 0.0],
            [0.1, 0, 0, 0, false, 0.0],
            [0.0, 1, 0, 0, false, 0.0],
            [0.0, 0, 1, 0, false, 0.0],
            [0.0, 0, 0, 1, false, -1.0],
            [0.0, 0, 0, -1, false, 1.0],
            [0.0, 0, 1, 1, false, -1.0],
            [0.0, 0, -1, -1, false, 1.0],
            [0.0, 0, -1, 1, false, -1.0],
            [0.0, 0, 1, -1, false, 1.0],
            [0.0, 1, 1, 1, false, -2.0],
            [0.0, 1, -1, 1, false, 0.0],
            [0.0, 1, 1, -1, false, 0.0],
            [0.0, 1, -1, -1, false, 2.0],
            [0.1, 0, 0, 0, false, 0.0],
            [0.1, 1, 0, 0, false, 0.0],
            [0.1, 0, 1, 0, false, 0.0],
            [0.1, 0, 0, 1, false, -1.0],
            [0.1, 1, 1, 0, false, -0.90909090909090984],
            [0.1, 1, 0, 1, false, -0.90909090909090984],
            [0.1, 1, 1, 1, false, -1.8181818181818188],
            [0.0, 0, 0, 0, true, 0.0],
            [0.1, 0, 0, 0, true, 0.0],
            [0.0, 1, 0, 0, true, 0.0],
            [0.0, 0, 1, 0, true, 0.0],
            [0.0, 0, 0, 1, true, -1.0],
            [0.0, 0, 0, -1, true, 1.0],
            [0.0, 0, 1, 1, true, -1.0],
            [0.0, 0, -1, -1, true, 1.0],
            [0.0, 0, -1, 1, true, -1.0],
            [0.0, 0, 1, -1, true, 1.0],
            [0.0, 1, 1, 1, true, -2.0],
            [0.0, 1, -1, 1, true, 0.0],
            [0.0, 1, 1, -1, true, 0.0],
            [0.0, 1, -1, -1, true, 2.0],
            [0.1, 0, 0, 0, true, 0.0],
            [0.1, 1, 0, 0, true, 0.0],
            [0.1, 0, 1, 0, true, 0.0],
            [0.1, 0, 0, 1, true, -1.0],
            [0.1, 1, 1, 0, true, -1.0],
            [0.1, 1, 0, 1, true, -0.90909090909090906],
            [0.1, 1, 1, 1, true, -1.9090909090909098],
            [0.035/12, 5*12, 0, -1000, false, 839.67086876847554],
            [0.035/12, 5*12, 0, -1000, true, 839.67086876847554],
            [0.05, 5, -70, -1000, false, 1086.5895334126164],
            [0.05, 5, -70, -1000, true, 1101.7427017598243],
            [0.035/12, 12*30, -2132.9622670919189, 0, false, 475000],
        ];
    }
}
