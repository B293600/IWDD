<?php
// Include database connection / login configuration
require_once 'login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Character encoding -->
    <meta charset="UTF-8">

    <!-- Page title -->
    <title>Statement of Credits - ProteinQuery</title>

    <!-- External CSS stylesheet -->
    <link rel="stylesheet" href="style_sheet.css">

    <style>
        /* Main page container styling */
        .container {
            padding: 40px;
            max-width: 1000px;
            margin: auto;
        }

        /* Center all headings */
        h2, h3 {
            text-align: center;
        }

        /* Spacing and readability for list items */
        li {
            margin-bottom: 14px;
            line-height: 1.6;
        }

        /* Ensure long URLs wrap properly */
        a {
            word-break: break-word;
        }

        /* Styling for GitHub repository section */
        .repo-link {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<!-- Top navigation bar -->
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="aves.php">Example Dataset</a>
    <a href="analysis_UI.php">Input Page</a>
    <a href="credits.php">Credit Page</a>
    <a href="about.php">About</a>
    <a href="help.php">Help</a>
</div>

<!-- Main content container -->
<div class="container">

    <!-- GitHub repository link -->
    <div class="repo-link">
        <strong>GitHub Repository:</strong>
        <a href="https://github.com/B293600/IWDD.git" target="_blank">
            https://github.com/B293600/IWDD.git
        </a>
    </div>

    <!-- Page heading -->
    <h2>Statement of Credits</h2>

    <!-- Course material section -->
    <h3>Course Material</h3>
    <ul>
        <li>
            Course Material — 
            <a href="https://bioinfmsc8.bio.ed.ac.uk/IWD2.html" target="_blank">
                https://bioinfmsc8.bio.ed.ac.uk/IWD2.html
            </a><br>
            Used as the primary reference for learning and applying concepts across PHP, HTML, CSS and JavaScript.
        </li>
    </ul>

    <!-- External resources section -->
    <h3>External Resources</h3>
    <ul>

        <!-- PHP -->
        <li>PHP sessions — <a href="https://www.w3schools.com/pHp/php_sessions.asp" target="_blank">https://www.w3schools.com/pHp/php_sessions.asp</a></li>
        <li>Output buffer — <a href="https://www.w3schools.com/php/ref_output_ob_start.asp" target="_blank">https://www.w3schools.com/php/ref_output_ob_start.asp</a></li>
        <li>PHP form handling — <a href="https://www.w3schools.com/php/php_forms.asp" target="_blank">https://www.w3schools.com/php/php_forms.asp</a></li>
        <li>PHP if statements — <a href="https://www.w3schools.com/php/keyword_endif.asp" target="_blank">https://www.w3schools.com/php/keyword_endif.asp</a></li>
        <li>PHP comments — <a href="https://www.w3schools.com/php/php_comments.asp#:~:text=PHP%20Single-line%20Comments,of%20line%2C%20will%20be%20ignored." target="_blank">https://www.w3schools.com/php/php_comments.asp</a></li>
        <li>HTML special characters — <a href="https://www.w3schools.com/php/_htmlspecialchars.asp" target="_blank">https://www.w3schools.com/php/_htmlspecialchars.asp</a></li>
        <li>PHP exception handling — <a href="https://www.w3schools.com/php/php_exception.asp" target="_blank">https://www.w3schools.com/php/php_exception.asp</a></li>

        <!-- HTML -->
        <li>HTML styles — <a href="https://www.w3schools.com/html/html_styles.asp" target="_blank">https://www.w3schools.com/html/html_styles.asp</a></li>
        <li>CSS styling — <a href="https://www.w3schools.com/html/html_css.asp" target="_blank">https://www.w3schools.com/html/html_css.asp</a></li>
        <li>HTML comments — <a href="https://www.w3schools.com/html/html_comments.asp" target="_blank">https://www.w3schools.com/html/html_comments.asp</a></li>
        <li>Meta tags — <a href="https://www.w3schools.com/tags/tag_meta.asp" target="_blank">https://www.w3schools.com/tags/tag_meta.asp</a></li>
        <li>HTML forms — <a href="https://www.w3schools.com/html/html_forms.asp" target="_blank">https://www.w3schools.com/html/html_forms.asp</a></li>
        <li>HTML links — <a href="https://www.w3schools.com/html/html_links.asp" target="_blank">https://www.w3schools.com/html/html_links.asp</a></li>
        <li>List tag — <a href="https://www.w3schools.com/tags/tag_li.asp#:~:text=The%20tag%20defines%20a,displayed%20with%20numbers%20or%20letters." target="_blank">https://www.w3schools.com/tags/tag_li.asp</a></li>
        <li>Ordered lists — <a href="https://www.w3schools.com/tags/tag_ol.asp" target="_blank">https://www.w3schools.com/tags/tag_ol.asp</a></li>
        <li>Div tag — <a href="https://www.w3schools.com/tags/tag_div.ASP#:~:text=The%20tag%20defines%20a,inside%20the%20tag!" target="_blank">https://www.w3schools.com/tags/tag_div.asp</a></li>
        <li>Footer tag — <a href="https://www.w3schools.com/tags/tag_footer.asp" target="_blank">https://www.w3schools.com/tags/tag_footer.asp</a></li>
        <li>Checkbox — <a href="https://www.w3schools.com/tags/att_input_type_checkbox.asp" target="_blank">https://www.w3schools.com/tags/att_input_type_checkbox.asp</a></li>
        <li>Hidden inputs — <a href="https://www.w3schools.com/tags/att_input_type_hidden.asp" target="_blank">https://www.w3schools.com/tags/att_input_type_hidden.asp</a></li>
        <li>HTML placeholder — <a href="https://www.w3schools.com/Tags/att_input_placeholder.asp" target="_blank">https://www.w3schools.com/Tags/att_input_placeholder.asp</a></li>
        <li>HTML value attribute — <a href="https://www.w3schools.com/tags/att_value.asp" target="_blank">https://www.w3schools.com/tags/att_value.asp</a></li>

        <!-- CSS -->
        <li>Hover — <a href="https://www.w3schools.com/cssref/sel_hover.php" target="_blank">https://www.w3schools.com/cssref/sel_hover.php</a></li>
        <li>Flexbox — <a href="https://www.w3schools.com/css/css3_flexbox.asp" target="_blank">https://www.w3schools.com/css/css3_flexbox.asp</a></li>
        <li>Animations — <a href="https://www.w3schools.com/css/css3_animations.asp" target="_blank">https://www.w3schools.com/css/css3_animations.asp</a></li>
        <li>Transitions — <a href="https://www.w3schools.com/css/css3_transitions.asp" target="_blank">https://www.w3schools.com/css/css3_transitions.asp</a></li>
        <li>Shadows — <a href="https://www.w3schools.com/css/css3_shadows.asp" target="_blank">https://www.w3schools.com/css/css3_shadows.asp</a></li>
        <li>Borders — <a href="https://www.w3schools.com/css/css3_borders.asp" target="_blank">https://www.w3schools.com/css/css3_borders.asp</a></li>
        <li>Class selector — <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/Reference/Selectors/Class_selectors" target="_blank">https://developer.mozilla.org/en-US/docs/Web/CSS/Reference/Selectors/Class_selectors</a></li>

        <!-- Navigation -->
        <li>Navigation bar — 
            <a href="https://www.w3schools.com/howto/howto_js_topnav.asp" target="_blank">Topnav</a>, 
            <a href="https://www.w3schools.com/bootstrap/bootstrap_navbar.asp" target="_blank">Bootstrap navbar</a>
        </li>

        <!-- JavaScript -->
        <li>JavaScript function — <a href="https://www.w3schools.com/js/js_function_intro.asp" target="_blank">https://www.w3schools.com/js/js_function_intro.asp</a></li>
        <li>Get element by ID — <a href="https://www.w3schools.com/jsref/met_document_getelementbyid.asp" target="_blank">https://www.w3schools.com/jsref/met_document_getelementbyid.asp</a></li>
        <li>Query selector — <a href="https://www.w3schools.com/jsref/met_document_queryselector.asp" target="_blank">https://www.w3schools.com/jsref/met_document_queryselector.asp</a></li>
        <li>Event listener — <a href="https://www.w3schools.com/js/js_htmldom_eventlistener.asp" target="_blank">https://www.w3schools.com/js/js_htmldom_eventlistener.asp</a></li>
        <li>If/Else — <a href="https://www.w3schools.com/js/js_if_else.asp" target="_blank">https://www.w3schools.com/js/js_if_else.asp</a></li>
        <li>Comparisons — <a href="https://www.w3schools.com/js/js_comparisons.asp" target="_blank">https://www.w3schools.com/js/js_comparisons.asp</a></li>
        <li>DOM overview — <a href="https://www.w3schools.com/js/js_htmldom.asp" target="_blank">https://www.w3schools.com/js/js_htmldom.asp</a></li>
        <li>ClassList — <a href="https://www.w3schools.com/jsref/prop_element_classlist.asp" target="_blank">https://www.w3schools.com/jsref/prop_element_classlist.asp</a></li>
        <li>Fetch API — <a href="https://www.w3schools.com/js/js_api_fetch.asp" target="_blank">https://www.w3schools.com/js/js_api_fetch.asp</a></li>
        <li>Timers — <a href="https://www.w3schools.com/js/js_timing.asp" target="_blank">https://www.w3schools.com/js/js_timing.asp</a></li>

        <!-- PHP / Data handling -->
        <li>File get contents — <a href="https://www.w3schools.com/php/func_filesystem_file_get_contents.asp" target="_blank">https://www.w3schools.com/php/func_filesystem_file_get_contents.asp</a></li>
        <li>JSON decode — <a href="https://www.w3schools.com/php/func_json_decode.asp" target="_blank">https://www.w3schools.com/php/func_json_decode.asp</a></li>
        <li>JSON encode — <a href="https://www.w3schools.com/php/func_json_encode.asp" target="_blank">https://www.w3schools.com/php/func_json_encode.asp</a></li>
        <li>PHP arrays — <a href="https://www.w3schools.com/php/php_arrays.asp" target="_blank">https://www.w3schools.com/php/php_arrays.asp</a></li>
        <li>Array functions — <a href="https://www.w3schools.com/php/php_arrays_functions.asp" target="_blank">https://www.w3schools.com/php/php_arrays_functions.asp</a></li>
        <li>Trim — <a href="https://www.w3schools.com/php/func_string_trim.asp" target="_blank">https://www.w3schools.com/php/func_string_trim.asp</a></li>
        <li>Explode — <a href="https://www.w3schools.com/php/func_string_explode.asp" target="_blank">https://www.w3schools.com/php/func_string_explode.asp</a></li>
        <li>Implode — <a href="https://www.w3schools.com/php/func_string_implode.asp" target="_blank">https://www.w3schools.com/php/func_string_implode.asp</a></li>
        <li>Wordwrap — <a href="https://www.w3schools.com/php/func_string_wordwrap.asp" target="_blank">https://www.w3schools.com/php/func_string_wordwrap.asp</a></li>
        <li>Uniqid — <a href="https://www.w3schools.com/php/func_misc_uniqid.asp" target="_blank">https://www.w3schools.com/php/func_misc_uniqid.asp</a></li>
        <li>Glob — <a href="https://www.w3schools.com/php/func_filesystem_glob.asp" target="_blank">https://www.w3schools.com/php/func_filesystem_glob.asp</a></li>
        <li>PDO prepared statements — <a href="https://www.w3schools.com/php/php_mysql_prepared_statements.asp" target="_blank">https://www.w3schools.com/php/php_mysql_prepared_statements.asp</a></li>
        <li>Shell exec — <a href="https://www.php.net/manual/en/function.shell-exec.php" target="_blank">https://www.php.net/manual/en/function.shell-exec.php</a></li>

        <!-- UI / Misc -->
        <li>Copy to clipboard — <a href="https://www.w3schools.com/howto/howto_js_copy_clipboard.asp" target="_blank">https://www.w3schools.com/howto/howto_js_copy_clipboard.asp</a></li>
        <li>Button — <a href="https://www.w3schools.com/tags/tag_button.asp" target="_blank">https://www.w3schools.com/tags/tag_button.asp</a></li>
        <li>Image tag — <a href="https://www.w3schools.com/tags/tag_img.asp" target="_blank">https://www.w3schools.com/tags/tag_img.asp</a></li>
        <li>Alert box — <a href="https://www.w3schools.com/js/js_popup.asp" target="_blank">https://www.w3schools.com/js/js_popup.asp</a></li>

    </ul>

    <!-- Generative AI usage section -->
    <h3>Use of Generative AI</h3>

    <p>
        ChatGPT (GPT-5-mini) was used to assist with debugging and adapting existing code examples.
    </p>

    <p>
        ChatGPT was primarily used for debugging. When errors occurred in PHP or JavaScript, relevant error messages were provided to the tool. It helped identify likely causes of issues and suggested possible corrections.
    </p>

    <p>
        The tool was also used to assist in adapting and modifying existing code obtained from external resources. It was used when there were difficulties implementing certain features, particularly the loading page animation.
    </p>

</div>

</body>
</html>
