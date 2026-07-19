<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Domain;
use App\Models\Skill;
use App\Models\JobOffer;

class ImportJobs extends Command
{
    protected $signature = 'import:jobs';
    protected $description = 'Import jobs from CSV file into database';

    public function handle(): void
    {
        $csvFile = fopen(storage_path("data/all_job_post.csv"), "r"); 
        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if ($firstline) { 
                $firstline = false; 
                continue; 
            }

            if (empty($data[2])) {
                continue; 
            }

            $domain = Domain::firstOrCreate([
                'domain_name' => trim($data[1] ?? 'General')
            ]);

            $jobOffer = JobOffer::create([
                'title' => trim($data[2]), 
                'company' => 'Entreprise Anonyme', 
                'description' => trim($data[3] ?? ''),
                'id_domain' => $domain->id_domain,
                'id_city' => null
            ]);

            if (!empty($data[4])) {
                $skillsArray = explode(',', $data[4]); 
                
                foreach ($skillsArray as $skillName) {
                    $skillName = trim($skillName); 
                    
                    if ($skillName != "") {
                        $skill = Skill::firstOrCreate([
                            'skill_name' => $skillName
                        ]);
                        
                        $jobOffer->skills()->attach($skill->id_skill);
                    }
                }
            }
        }
        fclose($csvFile);

        $this->info('Jobs imported successfully!');
    }
}