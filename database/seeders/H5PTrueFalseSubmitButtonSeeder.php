<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PTrueFalseSubmitButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pTrueFalseLibParams = ['name' => "H5P.TrueFalse", "major_version" => 1, "minor_version" => 6];
        $h5pTrueFalseQuizLib = DB::table('h5p_libraries')->where($h5pTrueFalseLibParams)->first();
        if ($h5pTrueFalseQuizLib) {
            DB::table('h5p_libraries')->where($h5pTrueFalseLibParams)->update([
                'semantics' => $this->updatedSemantics()
            ]);
        }
    }

    private function updatedSemantics() {
        return '[
          {
            "name": "media",
            "type": "group",
            "label": "Media",
            "importance": "medium",
            "fields": [
              {
                "name": "type",
                "type": "library",
                "label": "Type",
                "importance": "medium",
                "options": [
                  "H5P.Image 1.1",
                  "H5P.Video 1.5"
                ],
                "optional": true,
                "description": "Optional media to display above the question."
              },
              {
                "name": "disableImageZooming",
                "type": "boolean",
                "label": "Disable image zooming",
                "importance": "low",
                "default": false,
                "optional": true,
                "widget": "showWhen",
                "showWhen": {
                  "rules": [
                    {
                      "field": "type",
                      "equals": "H5P.Image 1.1"
                    }
                  ]
                }
              }
            ]
          },
          {
            "name": "question",
            "type": "text",
            "widget": "html",
            "label": "Question",
            "importance": "high",
            "enterMode": "p",
            "tags": [
              "strong",
              "em",
              "sub",
              "sup",
              "h2",
              "h3",
              "pre",
              "code"
            ]
          },
          {
            "name": "correct",
            "type": "select",
            "widget": "radioGroup",
            "alignment": "horizontal",
            "label": "Correct answer",
            "importance": "high",
            "options": [
              {
                "value": "true",
                "label": "True"
              },
              {
                "value": "false",
                "label": "False"
              }
            ],
            "default": "true"
          },
          {
            "name": "l10n",
            "type": "group",
            "common": true,
            "label": "User interface translations for True/False Questions",
            "importance": "low",
            "fields": [
              {
                "name": "trueText",
                "type": "text",
                "label": "Label for true button",
                "importance": "low",
                "default": "True"
              },
              {
                "name": "falseText",
                "type": "text",
                "label": "Label for false button",
                "importance": "low",
                "default": "False"
              },
              {
                "label": "Feedback text",
                "importance": "low",
                "name": "score",
                "type": "text",
                "default": "You got @score of @total points",
                "description": "Feedback text, variables available: @score and @total. Example: You got @score of @total possible points"
              },
              {
                "label": "Text for \"Check\" button",
                "importance": "low",
                "name": "checkAnswer",
                "type": "text",
                "default": "Check"
              },
              {
                "label": "Text for \"Show solution\" button",
                "importance": "low",
                "name": "showSolutionButton",
                "type": "text",
                "default": "Show solution"
              },
              {
                "label": "Text for \"Retry\" button",
                "importance": "low",
                "name": "tryAgain",
                "type": "text",
                "default": "Retry"
              },
              {
                "name": "wrongAnswerMessage",
                "type": "text",
                "label": "Wrong Answer",
                "importance": "low",
                "default": "Wrong answer"
              },
              {
                "name": "correctAnswerMessage",
                "type": "text",
                "label": "Correct Answer",
                "importance": "low",
                "default": "Correct answer"
              },
              {
                "name": "scoreBarLabel",
                "type": "text",
                "label": "Textual representation of the score bar for those using a readspeaker",
                "default": "You got :num out of :total points",
                "importance": "low"
              },
              {
                "name": "a11yCheck",
                "type": "text",
                "label": "Assistive technology description for \"Check\" button",
                "default": "Check the answers. The responses will be marked as correct, incorrect, or unanswered.",
                "importance": "low"
              },
              {
                "name": "a11yShowSolution",
                "type": "text",
                "label": "Assistive technology description for \"Show Solution\" button",
                "default": "Show the solution. The task will be marked with its correct solution.",
                "importance": "low"
              },
              {
                "name": "a11yRetry",
                "type": "text",
                "label": "Assistive technology description for \"Retry\" button",
                "default": "Retry the task. Reset all responses and start the task over again.",
                "importance": "low"
              }
            ]
          },
          {
            "name": "behaviour",
            "type": "group",
            "label": "Behavioural settings",
            "importance": "low",
            "description": "These options will let you control how the task behaves.",
            "fields": [
              {
                "name": "enableRetry",
                "type": "boolean",
                "label": "Enable \"Retry\" button",
                "importance": "low",
                "default": true
              },
              {
                "name": "enableSolutionsButton",
                "type": "boolean",
                "label": "Enable \"Show Solution\" button",
                "importance": "low",
                "default": true
              },
              {
                "name": "enableCheckButton",
                "type": "boolean",
                "label": "Enable \"Check\" button",
                "widget": "none",
                "importance": "low",
                "default": true,
                "optional": true
              },
              {
                "label": "Show confirmation dialog on \"Check\"",
                "importance": "low",
                "name": "confirmCheckDialog",
                "type": "boolean",
                "default": false
              },
              {
                "label": "Show confirmation dialog on \"Retry\"",
                "importance": "low",
                "name": "confirmRetryDialog",
                "type": "boolean",
                "default": false
              },
              {
                "label": "Automatically check answer",
                "importance": "low",
                "description": "Note that accessibility will suffer if enabling this option",
                "name": "autoCheck",
                "type": "boolean",
                "default": false
              },
              {
                "name": "feedbackOnCorrect",
                "label": "Feedback on correct answer",
                "importance": "low",
                "description": "This will override the default feedback text. Variables available: @score and @total",
                "type": "text",
                "maxLength": 2048,
                "optional": true
              },
              {
                "name": "feedbackOnWrong",
                "label": "Feedback on wrong answer",
                "importance": "low",
                "description": "This will override the default feedback text. Variables available: @score and @total",
                "type": "text",
                "maxLength": 2048,
                "optional": true
              }
            ]
          },
          {
            "label": "Check confirmation dialog",
            "importance": "low",
            "name": "confirmCheck",
            "type": "group",
            "common": true,
            "fields": [
              {
                "label": "Header text",
                "importance": "low",
                "name": "header",
                "type": "text",
                "default": "Finish ?"
              },
              {
                "label": "Body text",
                "importance": "low",
                "name": "body",
                "type": "text",
                "default": "Are you sure you wish to finish ?",
                "widget": "html",
                "enterMode": "p",
                "tags": [
                  "strong",
                  "em",
                  "del",
                  "u",
                  "code"
                ]
              },
              {
                "label": "Cancel button label",
                "importance": "low",
                "name": "cancelLabel",
                "type": "text",
                "default": "Cancel"
              },
              {
                "label": "Confirm button label",
                "importance": "low",
                "name": "confirmLabel",
                "type": "text",
                "default": "Finish"
              }
            ]
          },
          {
            "label": "Retry confirmation dialog",
            "importance": "low",
            "name": "confirmRetry",
            "type": "group",
            "common": true,
            "fields": [
              {
                "label": "Header text",
                "importance": "low",
                "name": "header",
                "type": "text",
                "default": "Retry ?"
              },
              {
                "label": "Body text",
                "importance": "low",
                "name": "body",
                "type": "text",
                "default": "Are you sure you wish to retry ?",
                "widget": "html",
                "enterMode": "p",
                "tags": [
                  "strong",
                  "em",
                  "del",
                  "u",
                  "code"
                ]
              },
              {
                "label": "Cancel button label",
                "importance": "low",
                "name": "cancelLabel",
                "type": "text",
                "default": "Cancel"
              },
              {
                "label": "Confirm button label",
                "importance": "low",
                "name": "confirmLabel",
                "type": "text",
                "default": "Confirm"
              }
            ]
          },{
            "name": "currikisettings",
            "type": "group",
            "label": "Curriki settings",
            "importance": "low",
            "description": "These options will let you control how the curriki studio behaves.",
            "optional": true,
            "fields": [
              {
                "label": "Do not Show Submit Button",
                "importance": "low",
                "name": "disableSubmitButton",
                "type": "boolean",
                "default": false,
                "optional": true,
                "description": "This option only applies to a standalone activity. The Submit button is required for grade passback to an LMS."
              },
              {
                "label": "Placeholder",
                "importance": "low",
                "name": "placeholder",
                "type": "boolean",
                "default": false,
                "optional": true,
                "description": "This option is a place holder. will be used in future"
              },
              {
                "label": "Curriki Localization",
                "description": "Here you can edit settings or translate texts used in curriki settings",
                "importance": "low",
                "name": "currikil10n",
                "type": "group",
                "fields": [
                  {
                    "label": "Text for \"Submit\" button",
                    "name": "submitAnswer",
                    "importance": "low",
                    "type": "text",
                    "default": "Submit",
                    "optional": true
                  },
                  {
                    "label": "Text for \"Placeholder\" button",
                    "importance": "low",
                    "name": "placeholderButton",
                    "type": "text",
                    "default": "Placeholder",
                    "optional": true
                  }
                ]
              }
            ]
          }
        ]';
    }
}
