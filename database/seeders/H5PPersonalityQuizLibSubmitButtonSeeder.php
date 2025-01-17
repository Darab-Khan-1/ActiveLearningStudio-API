<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PPersonalityQuizLibSubmitButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pPQLibParams = ['name' => "H5P.PersonalityQuiz", "major_version" => 1, "minor_version" => 0];
        $h5pPersonalityQuizLib = DB::table('h5p_libraries')->where($h5pPQLibParams)->first();
        if ($h5pPersonalityQuizLib) {
            DB::table('h5p_libraries')->where($h5pPQLibParams)->update(['semantics' => $this->updatedSemantics()]);
        }
    }

    private function updatedSemantics() {
        return '[
            {
                "name": "titleScreen",
                "type": "group",
                "label": "Title Screen",
                "expanded": true,
                "fields": [
                    {
                        "name": "title",
                        "type": "group",
                        "label": "Title",
                        "expanded": true,
                        "fields": [
                            {
                                "name": "text",
                                "type": "text",
                                "label": "Title"
                            },
                            {
                                "name": "display",
                                "type": "boolean",
                                "label": "Display Title",
                                "description": "Wether or not to show the title as a headline at the start of the personality quiz.",
                                "default": true
                            }
                        ]
                    },
                    {
                        "name": "image",
                        "type": "group",
                        "label": "Optional: Image",
                        "optional": true,
                        "fields": [
                            {
                                "name": "file",
                                "type": "image",
                                "label": "File"
                            },
                            {
                                "name": "alt",
                                "type": "text",
                                "label": "Alt text",
                                "description": "Alternative text if the browser is unable to load the image."
                            }
                        ]
                    },
                    {
                        "name": "skip",
                        "label": "Skip",
                        "type": "boolean",
                        "description": "Select if you want the quiz to start on the first question instead of the title screen.",
                        "default": false
                    }
                ]
            },
            {
                "name": "resultScreen",
                "type": "group",
                "label": "Result screen",
                "fields": [
                    {
                        "name": "animation",
                        "type": "select",
                        "label": "Animation",
                        "description": "The Wheel of Fortune animation will only use the images associated with personalities if ALL personalities has an image associated with them.",
                        "default": "none",
                        "options": [
                            {
                                "label": "None",
                                "value": "none"
                            },
                            {
                                "label": "Fade in",
                                "value": "fade-in"
                            },
                            {
                                "label": "Wheel of Fortune",
                                "value": "wheel"
                            }
                        ]
                    },
                    {
                        "name": "displayTitle",
                        "type": "boolean",
                        "label": "Display personality title?",
                        "default": true
                    },
                    {
                        "name": "displayDescription",
                        "type": "boolean",
                        "label": "Display description?",
                        "default": true
                    },
                    {
                        "name": "imagePosition",
                        "type": "select",
                        "label": "Personality Image Position",
                        "description": "Choose how to display the image, if there is any, of the result personality. Background will fill the entire H5P container and center the image. Inline will put the image as an image element between the result personality name and description.",
                        "default": "background",
                        "options": [
                            {
                                "value": "background",
                                "label": "Background"
                            },
                            {
                                "value": "inline",
                                "label": "Inline"
                            }
                        ]
                    }
                ]
            },
            {
                "name": "personalities",
                "type": "list",
                "label": "Personality",
                "entity": "personality",
                "min": 2,
                "max": 10,
                "field": {
                    "name": "personality",
                    "type": "group",
                    "label": "Personality",
                    "fields": [
                        {
                            "name": "name",
                            "type": "text",
                            "label": "Personality name",
                            "description": "The personality name will be used to associate answers with their respective personalities."
                        },
                        {
                            "name": "description",
                            "type": "text",
                            "label": "Description",
                            "maxLength": 450,
                            "widget": "textarea"
                        },
                        {
                            "name": "image",
                            "type": "group",
                            "label": "Optional: Image",
                            "optional": true,
                            "fields": [
                                {
                                    "name": "file",
                                    "type": "image",
                                    "label": "Image",
                                    "description": "An image to display on the result screen."
                                },
                                {
                                    "name": "alt",
                                    "type": "text",
                                    "label": "Alt text",
                                    "description": "Alternative text if the browser is unable to load the image."
                                }
                            ]
                        }
                    ]
                }
            },
            {
                "name": "questions",
                "type": "list",
                "label": "Questions",
                "entity": "question",
                "field": {
                    "name": "question",
                    "type": "group",
                    "label": "Question",
                    "fields": [
                        {
                            "name": "text",
                            "type": "text",
                            "label": "Question"
                        },
                        {
                            "name": "image",
                            "type": "group",
                            "label": "Optional: Image",
                            "description": "Image displayed at the top of the screen above or below the question text.",
                            "optional": true,
                            "fields": [
                                {
                                    "name": "file",
                                    "type": "image",
                                    "label": "File"
                                },
                                {
                                    "name": "alt",
                                    "type": "text",
                                    "label": "Alt text",
                                    "description": "Alternative text if the browser is unable to load the image."
                                }
                            ]
                        },
                        {
                            "name": "answers",
                            "type": "list",
                            "label": "Answers",
                            "entity": "answer",
                            "min": 2,
                            "max": 6,
                            "field": {
                                "name": "answer",
                                "type": "group",
                                "label": "Answer",
                                "fields": [
                                    {
                                        "name": "text",
                                        "type": "text",
                                        "label": "text"
                                    },
                                    {
                                        "name": "personality",
                                        "type": "text",
                                        "label": "Personalities",
                                        "description": "A comma separated list of personality names associated with this answer."
                                    },
                                    {
                                        "name": "image",
                                        "type": "group",
                                        "description": "Images associated with answers will not show up unless all answers associated with a question has an image attached.",
                                        "label": "Optional: Image",
                                        "optional": true,
                                        "fields": [
                                            {
                                                "name": "file",
                                                "type": "image",
                                                "label": "File"
                                            },
                                            {
                                                "name": "alt",
                                                "type": "text",
                                                "label": "Alt text",
                                                "description": "Alternative text if the browser is unable to load the image."
                                            }
                                        ]
                                    }
                                ]
                            }
                        }
                    ]
                }
            },
            {
                "label": "Start",
                "name": "startText",
                "type": "text",
                "default": "Start",
                "description": "Text displayed on the start quiz button on the title card."
            },
            {
                "label": "Progress text",
                "name": "progressText",
                "type": "text",
                "default": "@question of @total",
                "description": "Progress text, variables available: @question and @total. Example: @question of @total"
            },
            {
                "name": "retakeText",
                "label": "Retake",
                "type": "text",
                "default": "Retake the quiz",
                "description": "Retake text"
            },
            {
                "name": "animation",
                "type": "boolean",
                "label": "Animation",
                "default": true,
                "description": "Uncheck to turn off all animation"
            },
            {
                "name": "buttonColor",
                "type": "text",
                "label": "Button Accent",
                "description": "Change the color of the button borders and the animation color fill",
                "default": "4D5DAA",
                "widget": "colorSelector"
            },
            {
                "name": "progressbarColor",
                "type": "text",
                "label": "Progressbar Color",
                "description": "Determines the color of the progress bar",
                "default": "38B755",
                "widget": "colorSelector"
            },
            {
                "label": "Show Submit Answers Button",
                "name": "showSubmitAnswersButton",
                "type": "boolean",
                "default": true,
                "importance": "high",
                "optional": true
              },
              {
                "label": "Text for Submit Answers Button",
                "name": "submitAnswers",
                "type": "text",
                "default": "Submit Answers",
                "importance": "low",
                "common": true
              }
        ]';
    }
}
