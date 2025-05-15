<script setup lang="ts">
import { onMounted, onBeforeUnmount } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

//(work in progress for calendar)
// const datePickerWrapper = document.querySelector(".date-picker");
// const hiddenInput = datePickerWrapper?.querySelector(".hidden-date-picker") as HTMLInputElement | null;
// const selectedDateSpan = datePickerWrapper?.querySelector(".selected-date") as HTMLSpanElement | null;

// if (datePickerWrapper && hiddenInput && selectedDateSpan) {
//   datePickerWrapper.addEventListener("click", () => {
//     hiddenInput.showPicker(); // safe on modern browsers
//   });

//   hiddenInput.addEventListener("change", () => {
//     const date = new Date(hiddenInput.value);

//     const options: Intl.DateTimeFormatOptions = {
//       day: "2-digit",
//       month: "long",
//       year: "numeric",
//     };

//     const formattedDate = date.toLocaleDateString("id-ID", options);
//     selectedDateSpan.textContent = formattedDate;
//   });
// }

onMounted(() => {
    const generalInfo = document.querySelector('.general-info') as HTMLElement;
    const otherSections = document.querySelectorAll(
        '.architectural-section, .layout-section, .ambient-section, .timeline-section'
    );

    if (!generalInfo) return;

    const updateWidth = () => {
        const width = generalInfo.offsetWidth + 'px';
        otherSections.forEach((section) => {
            (section as HTMLElement).style.width = width;
        });
    };

    const resizeObserver = new ResizeObserver(() => {
        updateWidth();
    });

    resizeObserver.observe(generalInfo);

    updateWidth();

    onBeforeUnmount(() => {
        resizeObserver.disconnect();
    });
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <main class="request-design">
            <div class="content">
                <!-- General Information Section -->
                <section class="info-section">
                    <div class="general-info" style="min-width: 68%;">
                        <div class="info-column">
                            <h2 class="section-title">General Information</h2>
                            <div class="info-row">
                                <p class="info-label">Province</p>
                                <input type="text" placeholder="Enter Province" class="input-field-text" />
                            </div>
                            <div class="info-row">
                                <p class="info-label">City</p>
                                <input type="text" placeholder="Enter City" class="input-field-text" />
                            </div>
                            <div class="info-row">
                                <p class="info-label">Land Size</p>
                                <input type="text" placeholder="Enter Land Size" class="input-field-text" />
                            </div>
                            <div class="info-row">
                                <p class="info-label">Land Shape</p>
                                <input type="text" placeholder="Enter Land Shape" class="input-field-text" />
                            </div>
                            <div class="info-row">
                                <p class="info-label">Preview Picture</p>
                                <button class="add-btn">+</button>
                            </div>
                            <div class="info-row">
                                <p class="info-label">Budget</p>
                                <input type="text" placeholder="Enter Budget" class="input-field-text" />
                            </div>
                        </div>
                    </div>
                    <aside class="request-info">
                        <h2 class="section-title">Request</h2>
                        <p class="request-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                            laboris nisi ut aliquip ex ea commodo consequat.
                        </p>
                    </aside>
                </section>

                <!-- Architectural Style Section -->
                <section class="architectural-section">
                    <div class="info-column">
                        <h2 class="section-title">Architectural Style</h2>
                        <div class="info-row">
                            <p class="info-label">Property Type</p>
                            <input type="text" placeholder="Enter Property Type" class="input-field-text" />
                        </div>
                        <div class="info-row">
                            <p class="info-label">Style</p>
                            <input type="text" placeholder="Enter Style" class="input-field-text" />
                        </div>
                        <div class="info-row">
                            <p class="info-label">References</p>
                            <button class="add-btn">+</button>
                        </div>
                    </div>
                </section>

                <!-- Room Layout Section -->
                <section class="layout-section">
                    <div class="info-column">
                        <h2 class="section-title">Room Layout</h2>
                        <div class="info-row">
                            <p class="info-label">Facilities</p>
                            <input type="text" placeholder="Enter Facility" class="input-field-text" />
                            <div class="counter-controls">
                                <button class="counter-btn decrease">-</button>
                                <span class="counter-value">1</span>
                                <button class="counter-btn increase">+</button>
                            </div>
                        </div>
                        <a href="#" class="add-facility-link" style="margin-inline-start: 32%;">+ Add Facility</a>
                    </div>
                </section>

                <!-- Ambient Section -->
                <section class="ambient-section">
                    <div class="info-column">
                        <h2 class="section-title">Ambient</h2>
                        <div class="info-row">
                            <p class="info-label">Sun Orientation</p>
                            <input type="text" placeholder="Enter Sun Orientation" class="input-field-text" />
                        </div>
                        <div class="info-row">
                            <p class="info-label">Wind Orientation</p>
                            <input type="text" placeholder="Enter Wind Orientation" class="input-field-text" />
                        </div>
                        <div class="info-row">
                            <p class="info-label">More Information</p>
                            <textarea placeholder="Enter More information" class="input-field-text"></textarea>
                        </div>
                    </div>
                </section>

                <!-- Timeline Section -->
                <section class="timeline-section">
                    <div class="info-column">
                        <h2 class="section-title">Timeline</h2>
                        <div class="info-row">
                            <p class="info-label">Deadline</p>
                            <div class="date-picker">
                                <span class="selected-date">24 Mei 2025</span>
                                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/0e6de3ac079e04a06c19a709919467ec03deec0d?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34"
                                    class="calendar-icon" alt="Calendar icon" />
                                <input type="date" class="hidden-date-picker" />
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Footer -->
                <div class="form-footer">
                    <button class="send-btn">Send</button>
                </div>
            </div>
        </main>
    </AppLayout>
</template>

<style scoped>
.request-design {
    padding: 20px;
    background-color: #f4efef;
    width: 100%;
    height: 100%;
    box-sizing: border-box;
}

.content {
    max-width: auto;
    margin: 0 auto;
    padding: 20px;
}

.info-section {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.general-info,
.architectural-section,
.layout-section,
.ambient-section,
.timeline-section {
    border-radius: 30px;
    box-shadow: 0px 1px 29.2px 0px rgba(0, 0, 0, 0.25);
    padding: 20px;
    background-color: #fff;
    width: 100%;
    margin-bottom: 20px;
    min-width: 280px;
}

.request-info {
    border-radius: 30px;
    box-shadow: 0px 1px 29.2px 0px rgba(0, 0, 0, 0.25);
    padding: 20px;
    min-width: 32%;
    width: fit-content;
    background-color: #ae7a42;
    color: #fff;
}

.section-title {
    font-weight: 800;
    font-size: 18px;
    margin-bottom: 15px;
    font-family: Archivo, sans-serif;
}

.info-column {
    display: flex;
    flex-direction: column;
    gap: 15px;
    font-family: Archivo, sans-serif;
    color: #000;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
}

.info-label {
    display: flex;
    align-items: flex-start;
    font-weight: 500;
    font-size: 16px;
    width: 30%;

}

.input-field-text {
    flex-grow: 1;
    padding: 10px;
    border-radius: 9px;
    background-color: #f1ecec;
    border: none;
    font-size: 16px;
    font-family: Archivo, sans-serif;
}

.text-area-field {
    width: 100%;
    border-radius: 12px;
    background-color: #f1ecec;
    height: 150px;
    padding: 10px;
}

.add-btn {
    border-radius: 12px;
    padding: 10px 20px;
    font-family: Archivo, sans-serif;
    font-size: 16px;
    font-weight: 700;
    background-color: #f1ecec;
    border: none;
    cursor: pointer;
    width: 10%;
    height: 70px;
}

.counter-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.counter-btn {
    border-radius: 9px;
    width: 30px;
    height: 30px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.decrease {
    background-color: #ae7a42;
    color: #000;
}

.increase {
    background-color: #faae5c;
    color: #000;
}

.counter-value {
    font-weight: 500;
    text-align: center;
}

.date-picker {
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: #f1ecec;
    padding: 10px;
    border-radius: 9px;
}

.selected-date {
    font-weight: 500;
}

.calendar-icon {
    width: 24px;
    height: 24px;
}

.form-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 30px;
}

.send-btn {
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: 15px 30px;
    font-family: Archivo, sans-serif;
    font-size: 16px;
    font-weight: 800;
    background-color: #ae7a42;
    cursor: pointer;
}

@media (max-width: 768px) {
    .info-section {
        flex-direction: column;
    }

    .general-info,
    .request-info {
        flex: 1 1 100%;
        min-width: auto;
    }
}

.date-picker {
  position: relative;
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
}

.calendar-icon {
  width: 24px;
  height: 24px;
  cursor: pointer;
}

.hidden-date-picker {
  position: absolute;
  opacity: 0;
  pointer-events: none;
}
</style>