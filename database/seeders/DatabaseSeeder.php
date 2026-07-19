<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Domain;
use App\Models\Skill;
use App\Models\JobOffer;

class DatabaseSeeder extends Seeder
{
    public function run(): void
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

            // 1. إدخال أو جلب الـ Domain
            $domain = Domain::firstOrCreate([
                'domain_name' => trim($data[1] ?? 'General')
            ]);

            // 2. إدخال عرض الشغل بربط الـ Foreign Key الصحيح
            $jobOffer = JobOffer::create([
                'title' => trim($data[2]), 
                'company' => 'Entreprise Anonyme', 
                'description' => trim($data[3] ?? ''),
                'id_domain' => $domain->id_domain,
                'id_city' => null // حيت الـ CSV مافيهش المدن حالياً
            ]);

            // 3. تقطيع المهارات والربط ف الـ Pivot
            if (!empty($data[4])) {
                $skillsArray = explode(',', $data[4]); 
                
                foreach ($skillsArray as $skillName) {
                    $skillName = trim($skillName); 
                    
                    if ($skillName != "") {
                        $skill = Skill::firstOrCreate([
                            'skill_name' => $skillName
                        ]);
                        
                        // الربط فـ job_skill
                        $jobOffer->skills()->attach($skill->id_skill);
                    }
                }
            }
        }
        fclose($csvFile);
    }
}