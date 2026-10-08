<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\{Blog, Slider, Gallery, Service, City, VaccinationType, Page, Setting, Contact, Founder};
use App\Mail\ContactNotification;
use App\Services\MailService;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::active()->get();
        $galleries = Gallery::active()->where('show_on_home', true)->limit(8)->get();
        $services = Service::active()->limit(6)->get();
        $cities = City::active()->limit(6)->get();
        $featuredBlogs = Blog::published()->where('show_in_slider', true)->orderBy('created_at', 'desc')->limit(6)->get();
        $blogs = Blog::published()->orderBy('created_at', 'desc')->limit(6)->get();
        $faqs = \App\Models\Faq::active()->ordered()->limit(6)->get();
        $testimonials = \App\Models\Testimonial::active()->ordered()->limit(6)->get();
        $animalTypes = \App\Models\AnimalType::active()->ordered()->get();

        return view('frontend.home', compact('sliders', 'galleries', 'services', 'cities', 'blogs', 'featuredBlogs', 'faqs', 'testimonials', 'animalTypes'));
    }

    /**
     * Display FAQs page.
     */
    public function faqs()
    {
        $faqs = \App\Models\Faq::active()->ordered()->get();
        return view('frontend.faqs', compact('faqs'));
    }

    public function about()
    {
        $page = Page::where('slug', 'about')->first();
        $founders = Founder::active()->ordered()->get();
        return view('frontend.about', compact('page', 'founders'));
    }

    public function contact()
    {
        $page = Page::where('slug', 'contact')->first();
        return view('frontend.contact', compact('page'));
    }

    public function contactStore(Request $request)
    {
        if (!RecaptchaService::verify($request->input('g-recaptcha-response'))) {
            return back()->withInput()->withErrors(['g-recaptcha-response' => 'Please complete the reCAPTCHA verification.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($request->all());

        // Notify admin via email
        MailService::sendToAdmin(new ContactNotification($contact));

        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    public function blogs(Request $request)
    {
        $query = Blog::published()->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $blogs = $query->paginate(9);
        return view('frontend.blogs', compact('blogs'));
    }

    public function blogDetail($slug)
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();
        $blog->increment('views');
        $relatedPosts = $blog->relatedPosts(3);

        return view('frontend.blog-detail', compact('blog', 'relatedPosts'));
    }

    public function cityDetail($slug)
    {
        $city = City::active()->where('slug', $slug)->firstOrFail();
        $services = Service::active()->limit(6)->get();
        $vaccinationTypes = VaccinationType::active()->limit(6)->get();

        return view('frontend.city-detail', compact('city', 'services', 'vaccinationTypes'));
    }
    
    public function services()
    {
        $services = Service::active()->orderBy('title')->paginate(12);

        return view('frontend.services', compact('services'));
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('frontend.page', compact('page'));
    }
}
