<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendar Calculator</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>

<body class="bg-gray-100 text-gray-800">

<main class="max-w-5xl mx-auto p-6 space-y-6">

  <!-- HEADER -->
  <header class="text-center">
    <h1 class="text-3xl font-bold">Calendar Calculator</h1>
  </header>

  <!-- INPUT -->
  <section class="bg-white p-4 rounded-xl shadow">
    <form id="calculatorForm" class="grid md:grid-cols-3 gap-4 items-end">

      <div>
        <label class="block text-sm font-medium">Start Date</label>
        <input type="date" id="startDate"
          class="w-full border rounded-lg p-2">
      </div>

      <div>
        <label class="block text-sm font-medium">Required Hours</label>
        <input type="number" id="requiredHours"
          class="w-full border rounded-lg p-2">
      </div>

      <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
        Calculate
      </button>

    </form>
  </section>

  <!-- SUMMARY -->
  <section class="grid grid-cols-3 gap-4 text-center">

    <div class="bg-white p-4 rounded-xl shadow">
      <p class="text-sm text-gray-500">Start Date</p>
      <p id="displayStartDate" class="text-lg font-semibold">-</p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow">
      <p class="text-sm text-gray-500">Hours</p>
      <p id="displayHours" class="text-lg font-semibold">-</p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow">
      <p class="text-sm text-gray-500">Predicted End</p>
      <p id="displayEndDate" class="text-lg font-semibold">-</p>
    </div>

  </section>

  <!-- CALENDAR -->
  <section class="bg-white p-4 rounded-xl shadow">

    <div class="flex justify-between items-center mb-4">
      <button id="prevMonth" class="px-3 py-1 bg-gray-200 rounded">&lt;</button>
      <h2 id="calendarMonth" class="font-semibold">Month Year</h2>
      <button id="nextMonth" class="px-3 py-1 bg-gray-200 rounded">&gt;</button>
    </div>

    <table class="w-full text-center border-collapse">
      <thead>
        <tr class="bg-gray-200">
          <th class="p-2">Mon</th>
          <th class="p-2">Tue</th>
          <th class="p-2">Wed</th>
          <th class="p-2">Thu</th>
          <th class="p-2">Fri</th>
          <th class="p-2">Sat</th>
          <th class="p-2">Sun</th>
        </tr>
      </thead>

      <tbody id="calendarBody">
      </tbody>
    </table>

  </section>

  <!-- LEGEND -->
  <section class="bg-white p-4 rounded-xl shadow">
    <h3 class="font-semibold mb-2">Legend</h3>
    <ul class="space-y-1 text-sm">
      <li><span class="inline-block w-4 h-4 bg-orange-400 mr-2"></span>No Work</li>
      <li><span class="inline-block w-4 h-4 bg-red-500 mr-2"></span>Holiday</li>
      <li><span class="inline-block w-4 h-4 bg-green-400 mr-2"></span>Working</li>
      <li><span class="inline-block w-4 h-4 bg-green-700 mr-2"></span>Completed</li>
      <li><span class="inline-block w-4 h-4 bg-gray-400 mr-2"></span>Weekend</li>
    </ul>
  </section>

</main>
<script>
function loadCalendar(year, month) {
    $.ajax({
        url: "backend/bk_Calendar.php",
        method: "GET",
        data: { year, month },
        success: function (res) {
            $("#calendarBody").html(res);

            // Attach click AFTER inject (IMPORTANT)
            $("#calendarBody td").on("click", function () {
                $(this).toggleClass("bg-orange-400 bg-green-200");
            });
        }
    });

    // Update header
    const date = new Date(year, month - 1);
    const monthName = date.toLocaleString("default", { month: "long" });
    $("#calendarMonth").text(`${monthName} ${year}`);
}








//   const calendarBody = document.getElementById("calendarBody");
// const calendarMonth = document.getElementById("calendarMonth");

// let currentDate = new Date();

// function renderCalendar(date) {
//   calendarBody.innerHTML = "";

//   const year = date.getFullYear();
//   const month = date.getMonth();

//   // Month label
//   const monthName = date.toLocaleString("default", { month: "long" });
//   calendarMonth.textContent = `${monthName} ${year}`;

//   // First day of month (0 = Sunday)
//   let firstDay = new Date(year, month, 1).getDay();

//   // Convert to Monday start (0 = Monday)
//   firstDay = (firstDay === 0) ? 6 : firstDay - 1;

//   const daysInMonth = new Date(year, month + 1, 0).getDate();

//   let row = document.createElement("tr");

//   // Empty cells before month starts
//   for (let i = 0; i < firstDay; i++) {
//     const cell = document.createElement("td");
//     row.appendChild(cell);
//   }

//   for (let day = 1; day <= daysInMonth; day++) {
//     const cell = document.createElement("td");
//     cell.textContent = day;
//     cell.className = "p-2 border cursor-pointer";

//     const fullDate = new Date(year, month, day);
//     const weekDay = fullDate.getDay();

//     // WEEKEND (Grey)
//     if (weekDay === 0 || weekDay === 6) {
//       cell.classList.add("bg-gray-300");
//     } else {
//       // WORKING DAY (Green default)
//       cell.classList.add("bg-green-200");
//     }

//     // CLICK = toggle manual "No Work" (Orange)
//     cell.addEventListener("click", () => {
//       if (cell.classList.contains("bg-orange-400")) {
//         cell.classList.remove("bg-orange-400");
//         cell.classList.add("bg-green-200");
//       } else {
//         cell.classList.remove("bg-green-200");
//         cell.classList.add("bg-orange-400");
//       }
//     });

//     row.appendChild(cell);

//     // New row every Sunday (end of week)
//     if ((firstDay + day) % 7 === 0) {
//       calendarBody.appendChild(row);
//       row = document.createElement("tr");
//     }
//   }

//   // Append remaining row
//   if (row.children.length > 0) {
//     calendarBody.appendChild(row);
//   }
// }

// // NAVIGATION
// document.getElementById("prevMonth").onclick = () => {
//   currentDate.setMonth(currentDate.getMonth() - 1);
//   renderCalendar(currentDate);
// };

// document.getElementById("nextMonth").onclick = () => {
//   currentDate.setMonth(currentDate.getMonth() + 1);
//   renderCalendar(currentDate);
// };

// // INIT
// renderCalendar(currentDate);
</script>
</body>
</html>