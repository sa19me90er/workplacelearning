<?php


namespace App;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Translation\Translator;

class LearningActivityActingExportBuilder
{

    private $learningActivityActingCollection;

    public function __construct(Collection $learningActivityActingCollection)
    {
        $this->learningActivityActingCollection = $learningActivityActingCollection;
    }



    public function getJson()
    {
        $jsonArray = [];
        $this->learningActivityActingCollection->each(function(LearningActivityActing $activity) use (&$jsonArray) {
            $jsonArray[] = [
                "id" => $activity->laa_id,
                "date" => Carbon::createFromFormat("Y-m-d", $activity->date)->format("d-m-Y"),
                "situation" => $activity->situation,
                "timeslot" => $activity->getTimeslot(),
                "resourcePerson" => $activity->getResourcePerson(),
                "resourceMaterial" => $activity->getResourceMaterial(),
                "lessonsLearned" => $activity->lessonslearned,
                "learningGoal" => $activity->getLearningGoal(),
                "learningGoalDescription" => $activity->learningGoal->description,
                "supportWp" => $activity->support_wp !== null ? $activity->support_wp : "",
                "supportEd" => $activity->support_ed !== null ? $activity->support_ed : "",
                "competence" => __($activity->getCompetencies()->competence_label),
                "url" => route('process-acting-edit', ['id' => $activity->laa_id]),
                "evidence" => $activity->evidence_filename === null ? "-" :
                    route('evidence-download', ["id" => $activity->laa_id, "diskFileName" => $activity->evidence_disk_filename])
            ];
        });

        return json_encode($jsonArray);
    }

    public function getFieldLanguageMapping(Translator $translator) {
        $mapping = [];
         collect([
            'date',
            'situation',
            'timeslot',
            'resourcePerson',
            'resourceMaterial',
            'lessonsLearned',
            'learningGoal',
            'learningGoalDescription',
            'supportWp',
            'supportEd',
            'competence',
             'evidence'

        ])->each(function($field) use(&$mapping, $translator) { $mapping[$field] = $translator->get('process_export.' . $field); });
         return $mapping;
    }
}