<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>إنشاء تصويت</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../../public/css/form.css">
</head>
<body>
  <div class="form-container">
    <h2>🗳️ إنشاء تصويت جديد</h2>
    <form  method="POST" action="../Controllers/VoteController.php?action=create&
    project_id=<?= htmlspecialchars($project_id) ?>">

      <label for="question">سؤال التصويت:</label>
      <input type="text" name="question" id="question" required>

      <label for="status">الحالة:</label>
      <select name="status" id="status">
        <option value="open">مفتوح</option>
        <option value="closed">مغلق</option>
      </select>

      <button type="submit">إنشاء التصويت</button>
    </form>
  </div>
</body>
</html>
<style>
  /* General Body Styles */
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  line-height: 1.6;
  margin: 0;
  padding: 20px; /* Add some padding around the form */
  background-color: #f0f2f5; /* Light gray background */
  color: #333;
  direction: rtl; /* For Arabic language support */
  display: flex; /* For centering the form container */
  justify-content: center; /* For centering the form container */
  align-items: center; /* For centering the form container */
  min-height: 100vh; /* Ensure form is centered even on short pages */
}

/* Form Container */
.form-container {
  background-color: #ffffff;
  padding: 30px 40px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 500px; /* Adjust as needed */
  text-align: right; /* Default text alignment for RTL */
}

/* Form Heading */
.form-container h2 {
  color: #1c2e3f; /* Darker blue */
  margin-bottom: 30px;
  font-size: 1.8em;
  text-align: center; /* Center the heading */
  font-weight: 600;
}

/* Form Labels */
.form-container label {
  display: block;
  margin-bottom: 8px;
  color: #495057; /* Dark gray for labels */
  font-weight: 500;
  font-size: 1em;
}

/* Form Input Fields and Select */
.form-container input[type="text"],
.form-container select {
  width: 100%;
  padding: 12px 15px;
  margin-bottom: 20px;
  border: 1px solid #ced4da; /* Light gray border */
  border-radius: 6px;
  box-sizing: border-box; /* Include padding and border in the element's total width and height */
  font-size: 1em;
  color: #495057;
  transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.form-container input[type="text"]:focus,
.form-container select:focus {
  border-color: #80bdff; /* Blue border on focus */
  outline: 0;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Blue glow on focus */
}

/* Select specific styling to ensure consistent appearance */
.form-container select {
  appearance: none; /* Remove default system appearance */
  background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007bff%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E');
  background-repeat: no-repeat;
  background-position: left 0.75rem center; /* Adjust for RTL */
  background-size: 0.65em auto;
  padding-right: 15px; /* Default padding */
  padding-left: 2.5rem; /* Space for the arrow on RTL */
}


/* Submit Button */
.form-container button[type="submit"] {
  background-color: #007bff; /* Primary blue */
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1.1em;
  font-weight: 500;
  width: 100%; /* Make button full width */
  transition: background-color 0.3s ease;
  margin-top: 10px; /* Add some space above the button */
}

.form-container button[type="submit"]:hover {
  background-color: #0056b3; /* Darker blue on hover */
}

/* Responsive adjustments (optional, as the form is already quite narrow) */
@media (max-width: 480px) {
  .form-container {
    padding: 20px 25px;
  }

  .form-container h2 {
    font-size: 1.6em;
  }

  .form-container input[type="text"],
  .form-container select,
  .form-container button[type="submit"] {
    font-size: 0.95em;
  }
}
</style>