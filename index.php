<?php
require_once 'config/config.php';

if (is_logged_in()) {
    redirect('pages/beranda.php');
} else {
    redirect('pages/login.php');
}