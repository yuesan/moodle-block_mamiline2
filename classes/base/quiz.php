<?php
namespace block_minerva\base;

require_once(__DIR__ . "/../../../../mod/quiz/lib.php");

defined('MOODLE_INTERNAL') || die();

class quiz
{
    private $cache;
    private $context;
    private $user;

    function __construct($context)
    {
        global $USER;

        $this->context = $context;
        $this->user = $USER;
        $this->cache = new \stdClass();
    }

    /**
     * 小テストを取得する。
     *
     * @param $quizid
     *
     * @return mixed
     */
    public static function quiz($quizid)
    {
        global $DB;
        return $DB->get_record('quiz', ['id' => $quizid]);
    }

    public static function attempt($id)
    {
        global $DB;
        return $DB->get_record('quiz_attempts', ['id' => $id]);
    }

    /**
     * コース内の小テストをすべて取得する。
     *
     * @param $courseid
     * @return array
     * @throws \coding_exception
     */
    public function quizzes($courseid)
    {
        return get_coursemodules_in_course('quiz', $courseid);
    }

    /**
     * 小テストの評点を取得する。
     *
     * @param $quiz
     * @param $userid
     * @return array
     */
    public static function grade($quiz, $userid)
    {
        $grades = quiz_get_user_grades($quiz, $userid);
        $grade = array_shift($grades);
        return quiz_format_grade($quiz, $grade->rawgrade);
    }

    /**
     * 指定したユーザーの小テスト最高評点を取得する。
     *
     * @param $quiz
     * @param $userid
     * @return float
     */
    public static function best_grade($quiz, $userid)
    {
        return quiz_get_best_grade($quiz, $userid);
    }

    public static function recently_attempt($userid)
    {
        global $DB;
        $logs = $DB->get_records(
            "quiz_attempts",
            ["userid" => $userid, "state" => "finished"],
            "timemodified DESC",
            "*",
            0,10
        );
        $quiz = [];
        foreach($logs as $log){
            $quiz[$log->quiz] = self::quiz($log->quiz);
        }

        return $quiz;
    }
}