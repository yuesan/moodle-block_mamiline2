<?php

namespace block_minerva\misaka\rules;

class weather{
    public function get()
    {
        global $DB, $USER, $OUTPUT, $PAGE, $CFG;

        $PAGE->requires->js(new \moodle_url($CFG->wwwroot . "/blocks/misaka/js/rules/weather.js"));

        $message = new \stdClass();

        $message->score = 1;
        $message->text = \html_writer::tag('h5', '', ['id' => 'weather_position']);
        $message->text .= \html_writer::div('', '', ['id' => 'weather_string']);
        $message->text .= \html_writer::empty_tag('img', ['id' => 'weather_icon']);

        $message->text .= \html_writer::div('(気象データはOpenWeatherMapから取得しています。)', '');

        return $message;
    }
}