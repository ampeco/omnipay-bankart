<?php

namespace PaymentGateway\Client\Transaction\Base;

use PaymentGateway\Client\Schedule\ScheduleData;

/**
 * Trait ScheduleTrait
 *
 * @package PaymentGateway\Client\Transaction\Base
 */
trait ScheduleTrait {

    /**
     * @var ScheduleData
     */
    protected $schedule;

    /**
     * @return ScheduleData|null
     */
    public function getSchedule() {
        return $this->schedule;
    }

    /**
     * @param ScheduleData|null $schedule
     *
     * @return $this
     */
    public function setSchedule(ScheduleData $schedule = null) {
        $this->schedule = $schedule;

        return $this;
    }

}