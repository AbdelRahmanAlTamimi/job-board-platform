<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Resume;
use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\JobCategory;
use App\Models\JobApplication;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // seed an admin user if not exists
        User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'admin',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $jobData = json_decode(file_get_contents(database_path('data/job_data.json')), true);
        $jobApplications = json_decode(file_get_contents(database_path('data/job_applications.json')), true);

        // seed job categories
        foreach ($jobData['jobCategories'] as $category) {
            JobCategory::firstOrCreate([
                'name' => $category,
            ]);
        }

        // seed companies
        foreach ($jobData['companies'] as $company) {

            // crate company owner
            $companyOwner = User::firstOrCreate([
                'email' => fake()->unique()->safeEmail(),
            ], [
                'name' => fake()->name(),
                'password' => bcrypt('12345678'),
                'role' => 'company-owner',
                'email_verified_at' => now(),
            ]);

            Company::firstOrCreate([
                'name' => $company['name'],
            ], [
                'address' => $company['address'],
                'industry' => $company['industry'],
                'website' => $company['website'],
                'ownerId' => $companyOwner->id,
            ]);
        }

        // create job vacancies
        foreach ($jobData['jobVacancies'] as $job) {
            // get company and category that matching the job vacancy
            $company = Company::where('name', $job['company'])->firstOrFail();
            $category = JobCategory::where('name', $job['category'])->firstOrFail();

            JobVacancy::firstOrCreate([
                'title' => $job['title'],
                'location' => $job['location'],
            ], [
                'description' => $job['description'],
                'salary' => $job['salary'],
                'type' => $job['type'],
                'requirements' => is_array($job['technologies']) ? json_encode($job['technologies']) : $job['technologies'],
                'jobCategoryId' => $category->id,
                'companyId' => $company->id,
            ]);
        }

        // create job applications
        foreach ($jobApplications['jobApplications'] as $application) {
            // create job seeker
            $jobSeeker = User::firstOrCreate([
                'email' => fake()->unique()->safeEmail(),
            ], [
                'name' => fake()->name(),
                'password' => bcrypt('12345678'),
                'role' => 'job-seeker',
                'email_verified_at' => now(),
            ]);

            // create resume
            $resume = Resume::create([
                'jobSeekerId' => $jobSeeker->id,
                'fileName' => $application['resume']['fileName'],
                'fileUrl' => $application['resume']['fileUrl'],
                'contactDetails' => $application['resume']['contactDetails'],
                'summary' => $application['resume']['summary'],
                'skills' => $application['resume']['skills'],
                'experience' => $application['resume']['experience'],
                'education' => $application['resume']['education'],
            ]);

            // create job application
            JobApplication::firstOrCreate([
                'jobVacancyId' => JobVacancy::inRandomOrder()->first()->id,
                'resumeId' => $resume->id,
                'jobSeekerId' => $jobSeeker->id,
            ], [
                'status' => $application['status'],
                'aiGeneratedScore' => $application['aiGeneratedScore'],
                'aiGeneratedFeedback' => $application['aiGeneratedFeedback'],
            ]);
    }
}
}
