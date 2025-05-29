<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>قائمة التصويتات</title>
    <link rel="stylesheet" href="../../../../public/css/style.css">
</head>
<body>
    <div class="container">
        <h2>🗳️ قائمة التصويتات</h2>

        <a href="../Controllers/VoteController.php?action=create&project_id=<?= $project_id ?>">
            ➕ إنشاء تصويت جديد
        </a>

        <?php if (!empty($votes)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>السؤال</th>
                        <th>الحالة</th>
                        <th>إجراء</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($votes as $vote): ?>
                        <tr>
                            <td><?= htmlspecialchars($vote['question']) ?></td>
                            <td><?= htmlspecialchars($vote['status']) ?></td>
                            
                            <td>
                                <?php if ($vote['status'] === 'open'): ?>
                                    <!-- نعرض الخيارات المتاحة للتصويت -->
                                    <form action="../Controllers/VoteController.php?action=vote&
                                    vote_id=<?= $vote['vote_id'] ?>
                                    &project_id=<?= $vote['project_id'] ?>"
                                     method="POST" style="display:inline;">
                                        <label for="selected_option">اختر خيارك:</label>
                                        <select name="selected_option">
                                            <?php foreach (["ممتنع","لا","نعم"] as $option): ?>
                                                <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit">تصويت</button>
                                    </form>
                                <?php else: ?>
                                    مغلق
                                <?php endif; ?>
                                |
                                <a href="../Controllers/VoteController.php?action=result&vote_id=<?= $vote['vote_id'] ?>">عرض النتائج</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>لا توجد تصويتات لهذا المشروع.</p>
        <?php endif; ?>

    </div>
</body>
</html>
<style>
    /* General Body Styles */
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  line-height: 1.6;
  margin: 0;
  padding: 0;
  background-color: #f4f7f6; /* Light grayish-green background */
  color: #333;
  direction: rtl; /* For Arabic language support */
}

/* Container for the main content */
.container {
  width: 90%;
  max-width: 900px; /* Adjust as needed */
  margin: 30px auto;
  padding: 25px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Heading style */
.container h2 {
  color: #2c3e50; /* Dark blue-gray */
  margin-bottom: 25px;
  font-size: 2em;
  text-align: center;
  border-bottom: 2px solid #e0e0e0;
  padding-bottom: 15px;
}

/* "Create new vote" link styling */
.container > a {
  display: inline-block;
  background-color: #3498db; /* Bright blue */
  color: white;
  padding: 12px 20px;
  text-decoration: none;
  border-radius: 5px;
  margin-bottom: 25px;
  font-size: 1.1em;
  transition: background-color 0.3s ease;
}

.container > a:hover {
  background-color: #2980b9; /* Darker blue on hover */
}

/* Table Styling */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

table thead {
  background-color: #34495e; /* Dark slate blue */
  color: #ffffff;
}

table th {
  padding: 15px;
  text-align: right; /* RTL alignment */
  font-weight: 600;
  border-bottom: 2px solid #2c3e50;
}

table tbody tr {
  border-bottom: 1px solid #ecf0f1; /* Light gray border for rows */
}

table tbody tr:nth-child(even) {
  background-color: #f9f9f9; /* Zebra striping for readability */
}

table tbody tr:hover {
  background-color: #e8f4f8; /* Light blue hover effect */
}

table td {
  padding: 12px 15px;
  text-align: right; /* RTL alignment */
  vertical-align: middle;
}

/* Styling for action elements within the table */
table td form {
  display: flex; /* Align select and button nicely */
  align-items: center;
  gap: 10px; /* Space between select and button */
}

table td select {
  padding: 8px 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 0.95em;
  flex-grow: 1; /* Allow select to take available space */
  min-width: 120px; /* Minimum width for the select box */
}

table td button[type="submit"] {
  background-color: #27ae60; /* Green for submit */
  color: white;
  border: none;
  padding: 8px 15px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.95em;
  transition: background-color 0.3s;
}

table td button[type="submit"]:hover {
  background-color: #229954; /* Darker green on hover */
}

table td a {
  color: #007bff; /* Standard blue for links */
  text-decoration: none;
  margin: 0 5px; /* Spacing around the link */
}

table td a:hover {
  text-decoration: underline;
}

/* Text for closed votes or no votes */
table td:last-child span, /* For "مغلق" if you wrap it in a span */
.container > p {
  color: #7f8c8d; /* Grayish color for less emphasis */
  font-style: italic;
}

/* Specific styling for "مغلق" if not wrapped, or for generic text in the last cell */
table td:last-child {
  /* You can add specific styling here if needed, for example: */
  /* font-weight: bold; if the text "مغلق" is directly in td */
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .container {
    width: 95%;
    margin: 15px auto;
    padding: 15px;
  }

  .container h2 {
    font-size: 1.6em;
  }

  .container > a {
    font-size: 1em;
    padding: 10px 15px;
  }

  table, thead, tbody, th, td, tr {
    display: block; /* Make table elements stack on small screens */
  }

  table thead tr {
    position: absolute;
    top: -9999px;
    left: -9999px; /* Hide table headers but keep them accessible */
  }

  table tr {
    border: 1px solid #ccc;
    margin-bottom: 10px;
    border-radius: 5px;
  }

  table td {
    border: none;
    border-bottom: 1px solid #eee;
    position: relative;
    padding-left: 50%; /* Make space for the label */
    padding-right: 15px; /* Adjust padding for RTL */
    text-align: left; /* Adjust text alignment for RTL after pseudo-element */
    white-space: normal; /* Allow text to wrap */
  }

  table td:before {
    /* Use data-label attribute for pseudo-element content */
    position: absolute;
    top: 50%;
    right: 15px; /* Position to the right for RTL */
    left: auto;
    width: 45%; /* Adjust width for label */
    padding-right: 0; /* No padding-right for the pseudo-element itself */
    padding-left: 10px; /* Padding for separation */
    white-space: nowrap;
    transform: translateY(-50%);
    content: attr(data-label); /* Will pull from data-label attribute */
    font-weight: bold;
    text-align: right; /* Ensure label text is RTL */
  }

  /* Add data-label attributes to your td elements in HTML for this to work:
     <td data-label="السؤال"><?= htmlspecialchars($vote['question']) ?></td>
     <td data-label="الحالة"><?= htmlspecialchars($vote['status']) ?></td>
     <td data-label="إجراء">...</td>
  */

  table td form {
    flex-direction: column; /* Stack form elements vertically */
    align-items: stretch;
  }

  table td select,
  table td button[type="submit"] {
    width: 100%; /* Make form elements full width */
    margin-bottom: 8px;
  }

  table td button[type="submit"] {
    margin-bottom: 0; /* No margin for the last element in the form */
  }

  table td a {
    display: block; /* Make links take full width or more prominent */
    margin: 8px 0;
    text-align: center;
    padding: 8px;
    background-color: #f0f0f0;
    border-radius: 4px;
  }
}
</style>