@extends('landing.layouts.app')

@section('title', 'Digital Wedding Invitation - Create Beautiful Online Invitation | Teman Seakad')

@section('content')
    <!-- Hero Section -->
    @include('landing.components.hero')

    <!-- Features Section -->
    @include('landing.components.features')

    <!-- Live Preview Section -->
    @include('landing.components.preview')

    <!-- Wedding Music Section -->
    @include('landing.components.music')

    <!-- Showcase Grid Gallery Section -->
    @include('landing.components.showcase')

    <!-- How It Works Section -->
    @include('landing.components.workflow')

    <!-- Testimonials Section -->
    @include('landing.components.testimonials')

    <!-- Pricing Section -->
    @include('landing.components.pricing')

    <!-- FAQ Section -->
    @include('landing.components.faq')

    <!-- CTA Section -->
    @include('landing.components.cta')
@endsection
