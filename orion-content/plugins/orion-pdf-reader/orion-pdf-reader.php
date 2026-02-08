<?php
/*
Plugin Name: Orion PDF Reader
Description: Adds PDF reading capabilities to Orion CMS.
Version: 1.0
Author: Orion Team
*/

/**
 * Returns the HTML for the PDF viewer
 * 
 * @param string $url The URL of the PDF file
 * @return string HTML code for the viewer
 */
function orion_pdf_viewer($url) {
    if (empty($url)) return '';
    
    // Using Google Docs Viewer as fallback or native embed?
    // Native embed is best for modern browsers.
    // We wrap it in a nice container.
    
    $html = '<div class="pdf-viewer-container w-full h-[800px] border-4 border-libre-200 rounded-lg overflow-hidden shadow-xl bg-gray-50 flex flex-col">';
    
    // Toolbar (Fake)
    $html .= '<div class="bg-libre-700 text-white px-4 py-2 flex justify-between items-center text-sm shadow-md z-10">';
    $html .= '<span>Reading Mode</span>';
    $html .= '<a href="' . htmlspecialchars($url) . '" download class="bg-libre-500 hover:bg-libre-400 text-white px-3 py-1 rounded transition text-xs flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download PDF
              </a>';
    $html .= '</div>';

    // The iframe
    $html .= '<iframe src="' . htmlspecialchars($url) . '" type="application/pdf" width="100%" height="100%" class="flex-grow w-full h-full border-none">';
    $html .= '<p class="p-10 text-center">It appears your browser does not support embedded PDFs. <br> <a href="' . htmlspecialchars($url) . '" class="text-blue-600 underline">Download the PDF here</a>.</p>';
    $html .= '</iframe>';
    
    $html .= '</div>';
    
    return $html;
}
?>
