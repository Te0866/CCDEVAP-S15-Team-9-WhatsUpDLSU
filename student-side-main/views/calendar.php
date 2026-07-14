<section class="calendar-section">
    <div class="calendar-header">
        <button id="prevMonth" class="month-btn">&#10094;</button>
        <h2 id="monthTitle"></h2>
        <button id="nextMonth" class="month-btn">&#10095;</button>
    </div>
    <div class="calendar-grid" id="calendarGrid"></div>
</section>

<aside class="legend">
    <h3>Event Categories</h3>
    <div class="legend-item">
        <span class="box academic"></span>
        Academic
    </div>
    <div class="legend-item">
        <span class="box nonacademic"></span>
        Non-Academic
    </div>
    <div class="legend-item">
        <span class="box career"></span>
        Career
    </div>
</aside>

<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2 id="modalDate"></h2>
        <div id="modalEvents"></div>
    </div>
</div>
