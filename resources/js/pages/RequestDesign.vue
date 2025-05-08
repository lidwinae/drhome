<script setup lang="ts">
// import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
// eslint-disable-next-line vue/no-export-in-script-setup
// export default {
//     name: 'RequestDesign',
//     setup() {
//         return {}
//     }
// }
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <main class="request-design">
            <div class="content">
                <section class="info-section">
                    <div class="general-info">
                        <div class="info-labels">
                            <h2 class="section-title">General Information</h2>
                            <p class="info-label">Province</p>
                            <p class="info-label">City</p>
                            <p class="info-label">Land Size</p>
                            <p class="info-label">Land Shape</p>
                            <p class="info-label">Preview Picture</p>
                            <p class="info-label">Budget</p>
                        </div>
                        <div class="info-inputs">
                            <div class="input-field">
                                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/0e6de3ac079e04a06c19a709919467ec03deec0d?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34"
                                    class="input-icon" alt="Input icon" />
                            </div>
                            <div class="input-field">
                                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/0e6de3ac079e04a06c19a709919467ec03deec0d?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34"
                                    class="input-icon" alt="Input icon" />
                            </div>
                            <div class="input-field"></div>
                            <div class="input-field"></div>
                            <button class="add-btn">+</button>
                            <div class="input-field"></div>
                        </div>
                    </div>
                    <aside class="request-info">
                        <h2 class="section-title">Request</h2>
                        <p class="request-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                            eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                            enim ad minim veniam, quis nostrud exercitation ullamco laboris
                            nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                            in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                            nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                            sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                    </aside>
                </section>

                <section class="architectural-section">
                    <div class="arch-labels">
                        <h2 class="section-title">Architectural Style</h2>
                        <p class="arch-label">Property Type</p>
                        <p class="arch-label">Style</p>
                        <p class="arch-label">References</p>
                    </div>
                    <div class="arch-inputs">
                        <div class="input-field">
                            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/0e6de3ac079e04a06c19a709919467ec03deec0d?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34"
                                class="input-icon" alt="Input icon" />
                        </div>
                        <div class="input-field"></div>
                        <button class="add-btn">+</button>
                    </div>
                </section>

                <section class="layout-section">
                    <div class="layout-header">
                        <div class="layout-titles">
                            <h2 class="section-title">Room Layout</h2>
                            <p class="layout-subtitle">Facilities</p>
                        </div>
                        <div class="layout-controls">
                            <div class="input-field"></div>
                            <button class="add-facility-btn">+ Add Facility</button>
                        </div>
                        <div class="counter-controls">
                            <button class="counter-btn decrease">-</button>
                            <span class="counter-value">1</span>
                            <button class="counter-btn increase">+</button>
                        </div>
                    </div>
                </section>

                <section class="ambient-section">
                    <div class="ambient-labels">
                        <h2 class="section-title">Ambient</h2>
                        <p class="ambient-label">Sun Orientation</p>
                        <p class="ambient-label">Wind Orientation</p>
                        <p class="ambient-label">More information</p>
                    </div>
                    <div class="ambient-inputs">
                        <div class="input-field">
                            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/0e6de3ac079e04a06c19a709919467ec03deec0d?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34"
                                class="input-icon" alt="Input icon" />
                        </div>
                        <div class="input-field"></div>
                        <div class="text-area-field"></div>
                    </div>
                </section>

                <section class="timeline-section">
                    <div class="timeline-info">
                        <h2 class="section-title">Timeline</h2>
                        <p class="timeline-label">Deadline</p>
                    </div>
                    <div class="date-picker">
                        <span class="selected-date">24 Mei 2025</span>
                        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/0e6de3ac079e04a06c19a709919467ec03deec0d?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34"
                            class="calendar-icon" alt="Calendar icon" />
                    </div>
                </section>

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
}

.content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.info-section {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.general-info {
    border-radius: 30px;
    box-shadow: 0px 1px 29.2px 0px rgba(0, 0, 0, 0.25);
    padding: 20px;
    width: 68%;
    background-color: #fff;
    display: flex;
    gap: 20px;
}

.info-labels {
    width: 26%;
    display: flex;
    flex-direction: column;
    align-items: start;
    font-family: Archivo, sans-serif;
    font-size: 16px;
    color: #000;
    font-weight: 500;
}

.section-title {
    font-weight: 800;
    font-size: 18px;
    margin: 0 0 15px 0;
}

.info-label {
    margin: 15px 0 0;
}

.info-inputs {
    width: 74%;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.input-field {
    border-radius: 9px;
    padding: 10px;
    background-color: #f1ecec;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.input-icon {
    width: 24px;
    height: 24px;
}

.add-btn {
    border-radius: 12px;
    padding: 15px 25px;
    font-family: Archivo, sans-serif;
    font-size: 24px;
    color: #000;
    font-weight: 700;
    background-color: #f1ecec;
    border: none;
    cursor: pointer;
    align-self: flex-start;
}

.request-info {
    border-radius: 30px;
    box-shadow: 0px 1px 29.2px 0px rgba(0, 0, 0, 0.25);
    padding: 20px;
    width: 32%;
    background-color: #ae7a42;
    color: #fff;
}

.request-text {
    font-size: 14px;
    margin-top: 10px;
}

.architectural-section,
.layout-section,
.ambient-section,
.timeline-section {
    border-radius: 30px;
    box-shadow: 0px 1px 29.2px 0px rgba(0, 0, 0, 0.25);
    margin-top: 20px;
    padding: 20px;
    background-color: #fff;
}

.arch-labels {
    width: 24%;
    display: flex;
    flex-direction: column;
    align-items: start;
    font-family: Archivo, sans-serif;
    font-size: 16px;
    color: #000;
    font-weight: 500;
}

.arch-label {
    margin-top: 15px;
}

.arch-inputs {
    width: 76%;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.layout-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-family: Archivo, sans-serif;
    font-size: 16px;
    color: #000;
}

.layout-titles {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.layout-subtitle {
    font-weight: 500;
    margin: 0;
}

.layout-controls {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.add-facility-btn {
    border: none;
    background: none;
    font-weight: 500;
    font-size: 16px;
    color: #000;
    cursor: pointer;
    padding: 0;
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

.ambient-labels {
    width: 21%;
    display: flex;
    flex-direction: column;
    font-family: Archivo, sans-serif;
    font-size: 16px;
    color: #000;
    font-weight: 500;
}

.ambient-label {
    margin-top: 15px;
}

.ambient-inputs {
    width: 79%;
    margin-top: 15px;
}

.text-area-field {
    border-radius: 12px;
    height: 150px;
    background-color: #f1ecec;
    margin-top: 15px;
}

.timeline-info {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.timeline-label {
    font-weight: 500;
    margin: 0;
}

.date-picker {
    border-radius: 9px;
    padding: 10px;
    background-color: #f1ecec;
    display: flex;
    align-items: center;
    gap: 10px;
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

@media (max-width: 991px) {
    .content {
        padding: 15px;
    }

    .info-section {
        flex-direction: column;
    }

    .general-info,
    .request-info {
        width: 100%;
    }

    .info-labels,
    .arch-labels,
    .ambient-labels {
        width: 100%;
        margin-bottom: 15px;
    }

    .info-inputs,
    .arch-inputs,
    .ambient-inputs {
        width: 100%;
    }

    .input-field {
        width: 100%;
    }

    .layout-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}
</style>