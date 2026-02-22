<?php get_header(); ?>

<!-- Hero Section -->
<section class="relative bg-pmc-dark h-[600px] flex items-center overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0 opacity-40">
        <img src="orion1.jpeg" alt="Tactical Ops" class="w-full h-full object-cover">
    </div>
    
    <!-- Overlay Pattern -->
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20 z-0"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-pmc-dark via-pmc-dark/90 to-transparent z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 w-full">
        <div class="max-w-2xl">
            <div class="inline-block bg-pmc-green text-white text-xs font-bold uppercase tracking-[0.2em] py-1 px-3 mb-4">Global Security Solutions</div>
            <h1 class="text-5xl md:text-7xl font-stencil text-white mb-6 leading-none">
                Elite <span class="text-pmc-khaki">Defense</span><br>Contractors
            </h1>
            <p class="text-gray-300 text-lg mb-8 max-w-lg leading-relaxed border-l-4 border-pmc-khaki pl-6">
                Providing high-level security, risk management, and tactical support for governments and corporations worldwide.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="#contact" class="bg-pmc-khaki hover:bg-white text-pmc-dark font-bold uppercase tracking-wider py-4 px-8 text-center transition-colors clip-path-slant">
                    Request Support
                </a>
                <a href="#services" class="border-2 border-gray-500 hover:border-pmc-green text-gray-300 hover:text-white font-bold uppercase tracking-wider py-4 px-8 text-center transition-colors clip-path-slant">
                    Our Capabilities
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Bar -->
<section class="bg-pmc-green py-12 border-b-4 border-pmc-darkgreen relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-pmc-darkgreen/30">
            <div>
                <div class="text-4xl font-stencil text-white mb-1">500+</div>
                <div class="text-pmc-lightkhaki text-xs uppercase tracking-widest">Active Operatives</div>
            </div>
            <div>
                <div class="text-4xl font-stencil text-white mb-1">32</div>
                <div class="text-pmc-lightkhaki text-xs uppercase tracking-widest">Countries Deployed</div>
            </div>
            <div>
                <div class="text-4xl font-stencil text-white mb-1">100%</div>
                <div class="text-pmc-lightkhaki text-xs uppercase tracking-widest">Mission Success</div>
            </div>
            <div>
                <div class="text-4xl font-stencil text-white mb-1">24/7</div>
                <div class="text-pmc-lightkhaki text-xs uppercase tracking-widest">Command Center</div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-20 bg-pmc-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-stencil text-pmc-dark mb-4">Operational <span class="text-pmc-green">Capabilities</span></h2>
            <div class="h-1 w-20 bg-pmc-khaki mx-auto"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Service 1 -->
            <div class="bg-white border-b-4 border-pmc-green shadow-lg group hover:-translate-y-2 transition-transform duration-300">
                <div class="h-48 overflow-hidden relative">
                    <img src="close.jpeg" alt="Close Protection" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 grayscale group-hover:grayscale-0">
                    <div class="absolute top-0 right-0 bg-pmc-green text-white text-xs font-bold px-3 py-1">SEC-01</div>
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-stencil text-pmc-dark mb-3">Close Protection</h3>
                    <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                        Executive protection services for high-profile individuals in hostile environments. Threat assessment and secure transport.
                    </p>
                    <a href="#" class="text-pmc-green font-bold uppercase text-xs tracking-wider hover:text-pmc-dark transition-colors flex items-center gap-2">
                        Details <span class="text-lg">&rarr;</span>
                    </a>
                </div>
            </div>
            
            <!-- Service 2 -->
            <div class="bg-white border-b-4 border-pmc-khaki shadow-lg group hover:-translate-y-2 transition-transform duration-300">
                <div class="h-48 overflow-hidden relative">
                    <img src="logistic.jpeg" alt="Logistics" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 grayscale group-hover:grayscale-0">
                    <div class="absolute top-0 right-0 bg-pmc-khaki text-pmc-dark text-xs font-bold px-3 py-1">LOG-02</div>
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-stencil text-pmc-dark mb-3">Secure Logistics</h3>
                    <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                        Convoy security and asset transportation in high-risk zones. Supply chain protection and route reconnaissance.
                    </p>
                    <a href="#" class="text-pmc-green font-bold uppercase text-xs tracking-wider hover:text-pmc-dark transition-colors flex items-center gap-2">
                        Details <span class="text-lg">&rarr;</span>
                    </a>
                </div>
            </div>
            
            <!-- Service 3 -->
            <div class="bg-white border-b-4 border-pmc-dark shadow-lg group hover:-translate-y-2 transition-transform duration-300">
                <div class="h-48 overflow-hidden relative">
                    <img src="intel.jpeg" alt="Cyber" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 grayscale group-hover:grayscale-0">
                    <div class="absolute top-0 right-0 bg-pmc-dark text-white text-xs font-bold px-3 py-1">CYB-03</div>
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-stencil text-pmc-dark mb-3">Intelligence</h3>
                    <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                        Cyber warfare defense, surveillance, and counter-intelligence operations. Data protection and threat analysis.
                    </p>
                    <a href="#" class="text-pmc-green font-bold uppercase text-xs tracking-wider hover:text-pmc-dark transition-colors flex items-center gap-2">
                        Details <span class="text-lg">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Statement -->
<section id="about" class="py-20 bg-pmc-dark text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/diagmonds-light.png')] opacity-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-4xl font-stencil mb-6">Uncompromising <br><span class="text-pmc-khaki">Standards</span></h2>
                <div class="space-y-6 text-gray-400 leading-relaxed">
                    <p>
                        Founded by former special operations personnel, Orion PMC adheres to the strictest codes of conduct and operational excellence. We do not just provide manpower; we provide peace of mind in the world's most volatile regions.
                    </p>
                    <p>
                        Our operatives are recruited from Tier-1 units globally, ensuring that every team member possesses the tactical acumen and psychological resilience required for mission success.
                    </p>
                </div>
                <div class="mt-8 grid grid-cols-2 gap-4">
                    <div class="bg-white/5 p-4 border-l-2 border-pmc-green">
                        <h4 class="font-bold text-white mb-1">ISO 18788</h4>
                        <p class="text-xs text-gray-500">Certified Security Ops</p>
                    </div>
                    <div class="bg-white/5 p-4 border-l-2 border-pmc-green">
                        <h4 class="font-bold text-white mb-1">ICoC Signatory</h4>
                        <p class="text-xs text-gray-500">Intl Code of Conduct</p>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="absolute -inset-4 border-2 border-pmc-khaki/30"></div>
                <img src="pmc.jpeg" alt="Team Briefing" class="w-full grayscale hover:grayscale-0 transition-all duration-500 shadow-2xl relative z-10">
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-20 bg-pmc-light">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 md:p-12 shadow-2xl border-t-8 border-pmc-green">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-stencil text-pmc-dark">Secure Channel</h2>
                <p class="text-gray-500 mt-2">Encrypted communication line for mission requests.</p>
            </div>
            
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Codename / Name</label>
                        <input type="text" class="w-full bg-gray-50 border border-gray-300 p-3 focus:outline-none focus:border-pmc-green focus:ring-1 focus:ring-pmc-green transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Secure Email</label>
                        <input type="email" class="w-full bg-gray-50 border border-gray-300 p-3 focus:outline-none focus:border-pmc-green focus:ring-1 focus:ring-pmc-green transition-colors">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Operation Type</label>
                    <select class="w-full bg-gray-50 border border-gray-300 p-3 focus:outline-none focus:border-pmc-green focus:ring-1 focus:ring-pmc-green transition-colors">
                        <option>Risk Assessment</option>
                        <option>Close Protection</option>
                        <option>Logistics Security</option>
                        <option>Training</option>
                        <option>Other</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Briefing</label>
                    <textarea rows="4" class="w-full bg-gray-50 border border-gray-300 p-3 focus:outline-none focus:border-pmc-green focus:ring-1 focus:ring-pmc-green transition-colors"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-pmc-dark text-white font-bold uppercase tracking-widest py-4 hover:bg-pmc-green transition-colors clip-path-slant">
                    Transmit Request
                </button>
            </form>
        </div>
    </div>
</section>

<style>
    .clip-path-slant {
        clip-path: polygon(10% 0, 100% 0, 100% 100%, 0% 100%);
    }
    .clip-path-slant:hover {
        clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
    }
</style>

<?php get_footer(); ?>
