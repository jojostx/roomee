<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    public function getFaqs()
    {
        return [
            "General" => [
                "What is Roomee?" => "Roomee is a roommate search and recommendation service.",
                "Is Roomee a real estate service?" => "Although Roomee provides ease of accesing a roommate, roomee is not a typical real estate service.",
                "Is Roomee a dating platform?" => "No, Roomee does not provide dating and romantic match-making services.",
                "Is Roomee a social network platform?" => "No, Roomee is not a social network platform but roomee offers chat functionalities common to most social network.",
                "Is Roomee owned by any higher educational institution?" => "No, Roomee is an independent and free-to-use e-service.",
                "Is Roomee owned by any higher educational institution?" => "No, Roomee is an independent roommate finder e-service.",
                "Does roomee charge a fee for it's services?" => "No, Roomee is absolutely free to use.",
                "Do I need anything special to use Roomee?" => "No, you can access all of Roomee's services after signing up and completing your profile details.",
            ],
            "Roommate Recommendations" => [
                "How do I find a roommate on Roomee" => "After signing up and updating your profile details correctly,recommended roommates will be displayed to you, you could also search and filter roommates to your choice.",
                "How does roomee recommend roommates to me" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
                "Will I recieve roommate recommendations from the opposite sex?" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
                "Will Roomee recommend roommates from other locations?" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
                "Can I search for a roommate by myself on Roomee?" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
                "How many roommate requests can I send on Roomee?" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
                "How many Roomee users can I add to my favorites lists?" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
                "Can I restrict the number of roommate requests I can recieve?" => "Roomee uses a high-quality recommender algorithm based on accurate profile data analysis and comparisons.",
            ],
            "Messaging" => [
                "How do I send a message to a user I want as a roommate?" => "result1",
                "Who can I chat with on Roomee?" => "result1",
                "Are my chats private?" => "result1",
                "Can I chat with a roommate match on other messaging platform?" => "result1",
                "What information can i share with others in a chat?" => "result1",
                "Can I delete my chat history with a user?" => "result1",
            ],
            "Roomee's policies and reporting" => [
                "Can I block a user from sending me roommate request?" => "result",
                "How do I remove my account from the search results?" => "result",
                "What issues can I report a user for?" => "result",
                "Why is my account blocked from viewing a user?" => "result",
                "What can I do if my account is blocked by a user?" => "result",
                "Why is my account suspended?" => "result",
                "What can I do if my account is suspended on Roomee?" => "result",
            ],
            "Profile and Account" => [
                "How do I verify my email address?" => "result",
                "How can I change my password?" => "result",
                "How do I reset my password if I've forgotten it?" => "result",
                "Why can't I update all my profile information?" => "result",
                "How do I deactivate my account?" => "result",
                "How do I reactivate my account?" => "result",
            ],
        ];
    }
    public function index()
    {
        return view('pages.faqs', ['groups' => $this->getFaqs()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'feedback' => 'required|boolean'
        ]);

        Feedback::create([
            'feedback' => $request->feedback,
        ]);

        return response()->json([
            'success' => 'Feedback submitted successfully',
        ], 201);
    }
}
