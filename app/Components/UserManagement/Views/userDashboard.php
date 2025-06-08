<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Dashboard | MyApp</title>
  <link rel="stylesheet" href="../../../../public/css/userDashboard.css">
</head>
<body>
  <div class="dashboard-container">
    <header>
      <h1>Welcome, <?php echo $user->getName(); ?></h1>
      <p>Your dashboard at a glance</p>
    </header>

    <div class="dashboard-links">
      <a href="../../ProjectManagement/Controllers/ProjectController.php?action=list">📁 My Project</a>
      <a href="#">👤 Profile</a>
      <a href="#">🚪 Logout</a>
    </div>
    <div class="dashboard-info">
      <h2>Your Information</h2>
      <p><strong>Role:</strong> <?php echo $user->getRole(); ?></p>
      <p><strong>Major:</strong> <?php echo $user->getMajor(); ?></p>
  </div>
</body>
</html>
<style>/* Reset and Basic Box Sizing */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Body Styling */
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f0f2f5; /* Light grey background for the page */
  color: #333; /* Default text color */
  padding: 30px; /* Padding around the main container */
  line-height: 1.6; /* Improved readability */
}

/* Main Dashboard Container */
.dashboard-container {
  max-width: 900px; /* Max width of the dashboard */
  margin: auto; /* Center the dashboard */
  background: #ffffff; /* White background for the container */
  padding: 30px 40px; /* Increased padding for better spacing */
  border-radius: 10px; /* Rounded corners */
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1); /* Softer, more spread shadow */
}

/* Header Styling */
header {
  margin-bottom: 30px; /* Space below the header */
  padding-bottom: 20px; /* Space within the header before the border */
  border-bottom: 1px solid #e9ecef; /* Light border below header */
}

header h1 {
  font-size: 2em; /* Larger heading size */
  color: #2c3e50; /* Darker, more professional color */
  margin-bottom: 8px; /* Space below H1 */
}

header p {
  color: #5a6772; /* Softer color for subtitle */
  font-size: 1.1em;
}

/* Dashboard Navigation Links */
.dashboard-links {
  display: flex;
  gap: 15px; /* Space between links */
  margin-bottom: 35px; /* Space below the links section */
  flex-wrap: wrap; /* Allow links to wrap on smaller screens */
  padding-bottom: 25px;
  border-bottom: 1px solid #e9ecef; /* Light border below links */
}

.dashboard-links a {
  text-decoration: none;
  background: #007bff; /* Primary blue color */
  color: white;
  padding: 12px 22px; /* Slightly more padding */
  border-radius: 6px;
  transition: background-color 0.2s ease-in-out, transform 0.2s ease;
  font-size: 1em;
  font-weight: 500;
  display: flex; /* For icon alignment if you add icons */
  align-items: center; /* For icon alignment */
  gap: 8px; /* Space between icon and text */
}

.dashboard-links a:hover {
  background: #0056b3; /* Darker blue on hover */
  transform: translateY(-2px); /* Slight lift effect on hover */
}

/* User Information Section */
.dashboard-info {
  background-color: #f8f9fa; /* Very light grey background for this section */
  padding: 25px;
  border-radius: 8px;
  border: 1px solid #e0e0e0; /* Light border for the info section */
}

.dashboard-info h2 {
  font-size: 1.6em; /* Slightly smaller than main header */
  color: #343a40; /* Dark grey, almost black */
  margin-bottom: 20px; /* Space below the info title */
  padding-bottom: 10px;
  border-bottom: 1px solid #ced4da;
}

.dashboard-info p {
  font-size: 1.1em;
  color: #495057; /* Good readability color */
  margin-bottom: 12px; /* Space between info lines */
}

.dashboard-info p strong {
  color: #212529; /* Darker color for emphasis */
  margin-right: 8px; /* Space after the bolded label (for LTR) */
  /* For RTL, if you switch lang="ar", you might use margin-left */
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  body {
    padding: 20px;
  }

  .dashboard-container {
    padding: 20px 25px;
  }

  header h1 {
    font-size: 1.8em;
  }

  header p {
    font-size: 1em;
  }

  .dashboard-links {
    flex-direction: column; /* Stack links vertically on smaller screens */
    align-items: stretch; /* Make links take full width */
    gap: 12px;
  }

  .dashboard-links a {
    padding: 15px; /* Larger touch targets */
    text-align: center;
  }

  .dashboard-info h2 {
    font-size: 1.4em;
  }

  .dashboard-info p {
    font-size: 1em;
  }
}</style>
