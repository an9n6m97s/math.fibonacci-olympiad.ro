<?php
if (isset($_GET['view']) && strlen($_GET['view']) > 0) {
    $view = $_GET['view'];
    $mailData = getEmailLog($view);
    if (!$mailData) {
        echo '<div class="container mt-5 text-center">
            <div style="background-color: #ffcdcdff; color: #000000ff; border: 1px solid #ffbabaff; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                <strong>Warning:</strong> This email log does not exist or has been deleted.
            </div>
            <a href="/ucp/login" class="submit-btn btn-style-one">
                                        <span class="btn-wrap">
                                            <span class="text-one">Click here to login</span>
                                            <span class="text-two">Click here to login</span>
                                        </span>
                                    </a>';
        exit;
    }
    if ($mailData['user_id'] > 0) {
        if (!isLogged()) {
            echo '<div class="container mt-5 text-center">
            <div style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                <strong>Warning:</strong> You need to be logged in to view this content.
            </div>
            <a href="/ucp/login" class="submit-btn btn-style-one">
                                        <span class="btn-wrap">
                                            <span class="text-one">Click here to login</span>
                                            <span class="text-two">Click here to login</span>
                                        </span>
                                    </a>';
            exit;
        } else {
            $userId = getUserId();
            if ($mailData['user_id'] != $userId) {
                echo '<div class="container mt-5 text-center">
                <div style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                    <strong>Warning:</strong> You do not have permission to view this email log.
                </div>
                <a href="/ucp/login" class="submit-btn btn-style-one">
                                            <span class="btn-wrap">
                                                <span class="text-one">Click here to login</span>
                                                <span class="text-two">Click here to login</span>
                                            </span>
                                        </a>';
                exit;
            } else {
                echo $mailData['content'];
                echo '<div class="container mt-5 text-center">
                <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                    <strong>Success:</strong> You are viewing your email log.
                </div>
                <a href="/ucp/" class="submit-btn btn-style-one">
                                            <span class="btn-wrap">
                                                <span class="text-one">Go back to account</span>
                                                <span class="text-two">Go back to account</span>
                                            </span>
                                        </a>';
                exit;
            }
        }
    } else {
        echo $mailData['content'];
        echo '<div class="container mt-5 text-center">
                <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                    <strong>Success:</strong> You are viewing your email log.
                </div>
                <a href="/ucp/" class="submit-btn btn-style-one">
                                            <span class="btn-wrap">
                                                <span class="text-one">Go back to account</span>
                                                <span class="text-two">Go back to account</span>
                                            </span>
                                        </a>';
        exit;
    }
} else {
    header('Location: /');
    exit;
}
