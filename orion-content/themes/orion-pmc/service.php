<?php 
get_header(); 

// Define Services Data (In a real app, this would be from the database)
$services = [
    'close-protection' => [
        'title' => 'Close Protection',
        'subtitle' => 'High-Risk Personnel Security',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>',
        'description' => 'Our Close Protection operatives are drawn from Tier-1 special forces units. We provide comprehensive security details for diplomats, corporate executives, and high-net-worth individuals operating in hostile or unstable environments. Every detail is planned, from route reconnaissance to emergency extraction protocols.',
        'features' => [
            '24/7 Personal Security Detail (PSD)',
            'Armored Convoy Operations',
            'Counter-Surveillance',
            'Route Analysis & Threat Assessment',
            'Emergency Medical Support (TCCC certified)'
        ],
        'image' => 'close.jpeg'
    ],
    'secure-logistics' => [
        'title' => 'Secure Logistics',
        'subtitle' => 'Asset Transport & Supply Chain',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>',
        'description' => 'We guarantee the integrity of your supply chain in non-permissive environments. From high-value asset transport to essential sustainment for remote operations, our logistics network is secured by armed escorts and real-time tracking systems.',
        'features' => [
            'Armored Transport Vehicles',
            'Air & Sea Freight Security',
            'Warehousing & Inventory Protection',
            'Real-time GPS Tracking & Telemetry',
            'Cross-border Customs Clearance Support'
        ],
        'image' => 'logistic.jpeg'
    ],
    'intelligence' => [
        'title' => 'Intelligence',
        'subtitle' => 'Surveillance & Risk Analysis',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>',
        'description' => 'Information is the most valuable asset in modern warfare. Our intelligence division provides actionable insights through HUMINT, SIGINT, and OSINT capabilities. We help clients navigate complex geopolitical landscapes and anticipate threats before they materialize.',
        'features' => [
            'Threat & Vulnerability Assessments',
            'Due Diligence & Background Checks',
            'Drone/UAV Aerial Surveillance',
            'Cyber Threat Intelligence',
            'Political Risk Analysis'
        ],
        'image' => 'intel.jpeg'
    ],
    'rapid-response' => [
        'title' => 'Rapid Response',
        'subtitle' => 'Crisis Management & Extraction',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>',
        'description' => 'When crises occur, seconds count. Our Quick Reaction Forces (QRF) are on standby to deploy immediately for hostage rescue, medical evacuation (MEDEVAC), or facility reinforcement. We maintain air assets and tactical teams ready for immediate extraction.',
        'features' => [
            'Hostage Rescue & Negotiations',
            'Emergency Evacuation (K&R)',
            'Medical Evacuation (MEDEVAC)',
            'Natural Disaster Response',
            'Asset Recovery'
        ],
        'image' => 'https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80'
    ],
    'training' => [
        'title' => 'Training & Advisory',
        'subtitle' => 'Capacity Building',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>',
        'description' => 'We empower local forces and security teams through rigorous training programs. Our instructors are former Tier-1 operators who transfer skills in marksmanship, CQB, tactical driving, and leadership. We also provide strategic advisory for defense sector reform.',
        'features' => [
            'Advanced Marksmanship & CQB',
            'Tactical Combat Casualty Care (TCCC)',
            'Defensive Driving',
            'Mission Planning & Leadership',
            'Rules of Engagement (ROE) Compliance'
        ],
        'image' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80'
    ],
    'maritime' => [
        'title' => 'Maritime Security',
        'subtitle' => 'Anti-Piracy & Port Defense',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'description' => 'Protecting global shipping lanes and offshore assets. We deploy armed security teams on commercial vessels and provide comprehensive security assessments for ports and offshore energy platforms.',
        'features' => [
            'Vessel Protection Detachments (VPD)',
            'Anti-Piracy Operations',
            'Offshore Platform Security',
            'Port Facility Security Assessments',
            'Maritime Intelligence'
        ],
        'image' => 'https://images.unsplash.com/photo-1500917293891-ef795e70e1f6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80'
    ]
];

// Get Service ID from URL
$service_id = isset($_GET['s']) ? $_GET['s'] : '';
$current_service = isset($services[$service_id]) ? $services[$service_id] : null;

// If service not found, show list or 404 (showing list here for better UX)
if (!$current_service) {
    // Redirect to capabilities page or show simple list
    echo "<script>window.location.href='capabilities.php';</script>";
    exit;
}
?>

<!-- Hero Section -->
<section class="relative h-[60vh] flex items-center justify-center overflow-hidden border-b-4 border-pmc-gold">
    <div class="absolute inset-0 bg-slate-900">
        <!-- Background Image with Overlay -->
        <img src="<?php echo $current_service['image']; ?>" alt="<?php echo $current_service['title']; ?>" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-slate-900"></div>
    </div>
    
    <div class="relative z-10 text-center max-w-4xl mx-auto px-4">
        <div class="inline-block p-3 rounded-full bg-pmc-green/20 border border-pmc-gold/30 mb-6 backdrop-blur-sm">
            <svg class="w-12 h-12 text-pmc-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <?php echo $current_service['icon']; ?>
            </svg>
        </div>
        <h1 class="text-5xl md:text-7xl font-stencil uppercase tracking-widest text-white mb-4 text-shadow-lg">
            <?php echo $current_service['title']; ?>
        </h1>
        <p class="text-xl md:text-2xl text-pmc-khaki font-light tracking-wide uppercase">
            <?php echo $current_service['subtitle']; ?>
        </p>
    </div>
</section>

<!-- Content Section -->
<section class="py-20 bg-pmc-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white p-8 md:p-12 shadow-xl border-l-4 border-pmc-green">
                    <h2 class="text-3xl font-bold text-pmc-dark mb-6 font-stencil uppercase">Operational Brief</h2>
                    <div class="prose prose-lg text-slate-600 mb-8">
                        <p><?php echo $current_service['description']; ?></p>
                    </div>
                    
                    <h3 class="text-xl font-bold text-pmc-dark mb-4 uppercase tracking-wider">Key Capabilities</h3>
                    <ul class="space-y-4">
                        <?php foreach($current_service['features'] as $feature): ?>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-pmc-green text-pmc-khaki mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </span>
                            <span class="ml-3 text-lg text-slate-700"><?php echo $feature; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Contact Widget -->
                <div class="bg-pmc-dark text-white p-8 shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-pmc-gold/20 rounded-full blur-xl"></div>
                    <h3 class="text-2xl font-stencil text-pmc-gold mb-4 uppercase">Request Support</h3>
                    <p class="text-slate-400 mb-6 text-sm">Secure channel open. Contact our command center to discuss mission requirements.</p>
                    <a href="index.php#contact" class="block w-full py-3 px-4 bg-pmc-green hover:bg-pmc-darkgreen text-white text-center font-bold uppercase tracking-wider transition-colors clip-path-slant">
                        Contact Us
                    </a>
                </div>
                
                <!-- Other Services -->
                <div class="bg-white p-8 shadow-lg border border-gray-200">
                    <h3 class="text-xl font-bold text-pmc-dark mb-4 uppercase tracking-wider border-b border-gray-200 pb-2">Other Services</h3>
                    <ul class="space-y-3">
                        <?php foreach($services as $slug => $service): 
                            if($slug !== $service_id): ?>
                            <li>
                                <a href="service.php?s=<?php echo $slug; ?>" class="flex items-center text-slate-600 hover:text-pmc-green transition-colors group">
                                    <span class="w-1.5 h-1.5 bg-pmc-khaki group-hover:bg-pmc-green mr-2 transition-colors"></span>
                                    <?php echo $service['title']; ?>
                                </a>
                            </li>
                        <?php endif; endforeach; ?>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Related Stats -->
<section class="py-16 bg-pmc-dark text-white border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl font-stencil text-pmc-gold mb-2">100%</div>
                <div class="text-xs uppercase tracking-widest text-slate-500">Mission Success</div>
            </div>
            <div>
                <div class="text-4xl font-stencil text-pmc-gold mb-2">24/7</div>
                <div class="text-xs uppercase tracking-widest text-slate-500">Operational Readiness</div>
            </div>
            <div>
                <div class="text-4xl font-stencil text-pmc-gold mb-2">50+</div>
                <div class="text-xs uppercase tracking-widest text-slate-500">Countries Operated</div>
            </div>
            <div>
                <div class="text-4xl font-stencil text-pmc-gold mb-2">ISO</div>
                <div class="text-xs uppercase tracking-widest text-slate-500">Certified Processes</div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>