// Update the toggle interest function
document.getElementById("interestedBtn").addEventListener("click", async () => {
    if (!selectedEvent) return;

    try {
        const response = await fetch("?page=api-toggle", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `event_id=${selectedEvent.id}`
        });

        const result = await response.json();
        alert(result.message || "Interest updated successfully.");
        
        // Refresh page to update state
        location.reload();
    } catch (err) {
        console.error(err);
        alert("Unable to save interest.");
    }
});
