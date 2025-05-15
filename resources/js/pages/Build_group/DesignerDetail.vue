<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const designer = ref<{
  id: string;
  // Add other properties later for dummy
} | null>(null);

const isLoading = ref(true);


const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'DesignerDetail',
    href: '/detail',
  },
];

// Dummy data structure matching your API
const dummyData = {
  id: "1",
  name: "Ayu Nabila",
  country: "Indonesia",
  origin_city: "Malang",
  specialty: "UI Designer",
  photo_url: "https://imagedelivery.net/LBWXYQ-XnKSYxbZ-NuYGqQ/dacfb96d-c3fe-42d4-912d-083418f0f300/avatarhd",
  description: "Desainer antarmuka berpengalaman dengan fokus pada aksesibilitas dan desain mobile.",
  portfolio: [
    {
      title: "Tomaro Inn",
      location: "Japan",
      style: "Modern",
      year: "2022",
      description: "Desain interior modern untuk hotel boutique di Tokyo",
      image_url: "https://cdn.builder.io/api/v1/image/assets/TEMP/5da394aedd3987e924387ee0f3b04b74f73c5777?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34"
    },
    {
      title: "Bamboo House",
      location: "Bali",
      style: "Tropical",
      year: "2021",
      description: "Rumah ekologis dengan material bambu alami",
      image_url: "https://cdn.builder.io/api/v1/image/assets/TEMP/032ad442db118aae7b4064a4720559bae03fdb2a?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34"
    },
    {
      title: "Urban Loft",
      location: "Jakarta",
      style: "Industrial",
      year: "2023",
      description: "Apartemen gaya industrial di kawasan perkotaan",
      image_url: "https://cdn.builder.io/api/v1/image/assets/TEMP/b5eb1f0fbab2ef0b7a28c9d7171bbd84abfedca7?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34"
    }
  ],
  education: [
    {
      degree: "Sarjana Desain Interior",
      university: "Institut Teknologi Bandung",
      year: "2015-2019"
    }
  ],
  experience: [
    {
      position: "Lead Designer",
      company: "HomeSpace Design Studio",
      period: "2020-Present"
    }
  ],
  contact: {
    email: "ayu.nabila@design.com",
    phone: "+62 812-3456-7890",
    address: "Jl. Design No. 123, Malang, Jawa Timur"
  },
  services: [
    "Desain Interior Rumah",
    "Konsultasi Ruang Kecil"
  ],
  awards: [
    {
      name: "Best Residential Design 2022",
      organization: "Indonesia Design Awards"
    }
  ]
};

// Recommendations data
const recommendationsData = Array(3).fill({
  name: "Ali Rohmadanu",
  role: "Contractor",
  imageUrl: "https://cdn.builder.io/api/v1/image/assets/TEMP/a985a5b23772e5cb50f72e965d8d1e9b463ff3c2?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34"
});

// Fetch designer data
onMounted(async () => {
  try {
    // In development, use dummy data
    if (import.meta.env.DEV) {
      designer.value = dummyData;
    } else {
      const response = await axios.get(`/api/designers/${route.params.id}`);
      designer.value = {
        ...response.data,
        photo_url: response.data.photo
          ? `data:image/jpeg;base64,${response.data.photo}`
          : '/images/default_Avatar.png'
      };
    }
  } catch (error) {
    console.error('Error fetching designer:', error);
    designer.value = dummyData;
  } finally {
    isLoading.value = false;
  }
});

function handleImageError(event: Event) {
  const target = event.target as HTMLImageElement;
  target.src = '/images/default_Avatar.png';
}
</script>


<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <!-- Main Content -->
    <div class="main-content">
      <div v-if="isLoading" class="loading-state">
        <p>Loading designer profile...</p>
      </div>

      <div v-else-if="designer" class="content-grid">
        <div class="left-column">
          <!-- Profile Section -->
          <section class="profile-section">
            <div class="profile-header">
              <img
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/6f6b2bd1fb346d2b1ae7a3789c1936af4de2b45f?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34"
                alt="" class="cover-image" />
              <h2 class="profile-name">{{ designer.name }}</h2>
            </div>
            <div class="profile-content">
              <div class="profile-info">
                <img :src="designer.photo_url" alt="" class="profile-picture" @error="handleImageError" />
                <div class="info-details">
                  <div class="tags">
                    <span class="tag">{{ designer.origin_city }}</span>
                    <span class="tag">{{ designer.specialty }}</span>
                  </div>
                  <p class="location">{{ designer.origin_city }}, {{ designer.country }}</p>
                  <p class="role">{{ designer.specialty }}</p>
                </div>
                <Link :href="route('designer.request', { id: designer.id })" class="request-button">
                Request
                </Link>
              </div>
            </div>
            <div class="about-section">
              <h3 class="about-title">About</h3>
              <p class="about-text">{{ designer.description }}</p>

              <div class="section-block">
                <h4>Education</h4>
                <div v-for="(edu, index) in designer.education" :key="index" class="education-item">
                  <h5>{{ edu.degree }}</h5>
                  <p>{{ edu.university }} ({{ edu.year }})</p>
                </div>
              </div>

              <div class="section-block">
                <h4>Experience</h4>
                <div v-for="(exp, index) in designer.experience" :key="index" class="experience-item">
                  <h5>{{ exp.position }}</h5>
                  <p>{{ exp.company }} ({{ exp.period }})</p>
                </div>
              </div>
            </div>
          </section>

          <!-- Portfolio Section -->
          <section class="portfolio-section">
            <div class="portfolio-content">
              <h2 class="portfolio-title">Portofolio</h2>

              <div v-if="designer?.portfolio?.length" class="portfolio-container">
                <div class="portfolio-grid">
                  <article v-for="(item, index) in designer.portfolio" :key="index" class="portfolio-card">
                    <div class="portfolio-image-container">
                      <img :src="item.image_url" :alt="item.title || 'Portfolio item'" class="portfolio-image"
                        @error="handleImageError" loading="lazy" />
                      <div class="portfolio-overlay">
                        <div class="portfolio-info">
                          <h3 class="portfolio-item-title">{{ item.title }}</h3>
                          <div class="portfolio-meta">
                            <span class="portfolio-location">{{ item.location }}</span>
                            <span class="portfolio-style">{{ item.style }}</span>
                          </div>
                          <p class="portfolio-year">{{ item.year }}</p>
                          <p class="portfolio-description">{{ item.description }}</p>
                        </div>
                      </div>
                    </div>
                  </article>
                </div>
              </div>

              <div v-else class="empty-portfolio">
                <p>No portfolio items available</p>
              </div>
            </div>
          </section>
        </div>

        <!-- Right Column -->
        <div class="right-column">
          <aside class="recommendations-sidebar">
            <h3 class="recommendations-title">Rekomendasi Untukmu</h3>
            <div v-for="(user, index) in recommendationsData" :key="index" class="recommendation-card">
              <img :src="user.imageUrl" :alt="user.name" class="user-avatar" />
              <div class="user-info">
                <h4 class="user-name">{{ user.name }}</h4>
                <p class="user-role">{{ user.role }}</p>
              </div>
            </div>
          </aside>
        </div>
      </div>

      <div v-else class="error-state">
        <p>Designer not found</p>
      </div>
    </div>
  </AppLayout>
</template>



<style scoped>
.brand-title {
  color: #ae7a42;
  font-family: Archivo, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 51px;
  font-weight: 700;
  margin: 0;
}

.content-grid {
  display: flex;
  gap: 20px;
}

.left-column {
  width: 68%;
}

.right-column {
  width: 32%;
}

.profile-section {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 76px;
}

.profile-header {
  border-radius: 30px;
  position: relative;
  min-height: 358px;
  display: flex;
  align-items: flex-end;
  text-shadow: 4px 6px 5.8px rgba(0, 0, 0, 0.25);
}

.cover-image {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 30px;
}

.profile-name {
  position: relative;
  color: #fff;
  font-family: Archivo, sans-serif;
  font-size: 51px;
  font-weight: 700;
  padding: 0 70px 40px;
  margin: 0;
}

.profile-content {
  margin-top: -125px;
  z-index: 1;
}

.profile-info {
  display: flex;
  gap: 20px;
  align-items: flex-start;
  padding: 0 20px;
}

.profile-picture {
  width: 35%;
  aspect-ratio: 1;
  border-radius: 50%;
  object-fit: contain;
}

.info-details {
  flex: 1;
  margin-top: 145px;
}

.tags {
  display: flex;
  gap: 13px;
}

.tag {
  color: #fff;
  background-color: #faae5c;
  border-radius: 10.679px;
  padding: 7px 20px;
  font-size: 12px;
}

.location {
  color: #714c25;
  font-size: 20px;
  margin-top: 24px;
  font-weight: 700;
}

.role {
  color: #714c25;
  font-size: 15px;
  margin-top: 5px;
  font-weight: 700;
}

.request-button {
  color: #fff;
  background-color: #ae7a42;
  border: none;
  border-radius: 32.213px;
  padding: 18px 39px;
  font-family: Archivo, sans-serif;
  font-size: 20px;
  font-weight: 800;
  margin-top: 187px;
  cursor: pointer;
}

.about-section {
  background-color: #faae5c;
  border-radius: 30px;
  padding: 37px 64px 69px;
  box-shadow: 0px 4px 27.7px 0px rgba(0, 0, 0, 0.25);
}

.about-title {
  color: #fff;
  font-size: 36px;
  font-weight: 700;
  margin: 0 0 25px;
}

.about-text {
  color: #fff;
  font-size: 24px;
  margin: 0;
}

.section-block {
  margin-top: 30px;
}

.section-block h4 {
  color: #fff;
  font-size: 24px;
  margin-bottom: 15px;
}

.education-item,
.experience-item {
  margin-bottom: 20px;
}

.education-item h5,
.experience-item h5 {
  color: #fff;
  font-size: 18px;
  margin-bottom: 5px;
}

.education-item p,
.experience-item p {
  color: #fff;
  font-size: 16px;
}

.recommendations-sidebar {
  border-radius: 30px;
  box-shadow: -1px 6px 20.4px 0px rgba(0, 0, 0, 0.25);
  padding: 35px 80px 35px 37px;
  background-color: #fff;
  width: 100%;
}

.recommendations-title {
  font-size: 24px;
  font-weight: 800;
  font-family: Archivo, sans-serif;
  margin: 0 0 23px;
}

.recommendation-card {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 31px;
}

.recommendation-card:first-child {
  margin-top: 0;
}

.user-avatar {
  width: 77px;
  height: 77px;
  border-radius: 50%;
  object-fit: contain;
}

.user-info {
  flex: 1;
}

.user-name {
  font-size: 20px;
  font-weight: 700;
  margin: 0;
}

.user-role {
  font-size: 11px;
  font-weight: 500;
  margin: 14px 0 0;
}

.portfolio-section {
  width: 100%;
  padding: 60px 20px;
  margin-top: 30px;
  margin-bottom: 30px;
  background-color: #f9f9f9;
}

.portfolio-content {
  max-width: 1334px;
  margin: 0 auto;
}

.portfolio-title {
  color: #AE7A42;
  font-family: Archivo, sans-serif;
  font-size: 36px;
  font-weight: 700;
  margin-bottom: 40px;
  text-align: center;
}

.portfolio-container {
  width: 100%;
  overflow: hidden;
}

.portfolio-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 30px;
  justify-items: center;
}

.portfolio-card {
  width: 100%;
  max-width: 400px;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.portfolio-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.portfolio-image-container {
  position: relative;
  width: 100%;
  padding-top: 75%;
  /* 4:3 aspect ratio */
  overflow: hidden;
}

.portfolio-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.portfolio-card:hover .portfolio-image {
  transform: scale(1.05);
}

.portfolio-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
  color: white;
  padding: 20px;
  opacity: 0;
  transition: opacity 0.3s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}

.portfolio-card:hover .portfolio-overlay {
  opacity: 1;
}

.portfolio-info {
  transform: translateY(20px);
  transition: transform 0.3s ease;
}

.portfolio-card:hover .portfolio-info {
  transform: translateY(0);
}

.portfolio-item-title {
  font-size: 1.5rem;
  margin-bottom: 10px;
  color: white;
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
}

.portfolio-meta {
  display: flex;
  gap: 10px;
  margin-bottom: 8px;
}

.portfolio-location,
.portfolio-style {
  font-size: 0.9rem;
  background-color: rgba(174, 122, 66, 0.8);
  padding: 4px 10px;
  border-radius: 20px;
}

.portfolio-year {
  font-size: 0.9rem;
  margin-bottom: 10px;
  color: #FAE5CC;
}

.portfolio-description {
  font-size: 0.95rem;
  line-height: 1.4;
  margin-top: 8px;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.empty-portfolio {
  text-align: center;
  padding: 40px;
  color: #AE7A42;
  font-size: 1.2rem;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
  .portfolio-grid {
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  }
}

@media (max-width: 768px) {
  .portfolio-section {
    padding: 40px 15px;
  }

  .portfolio-title {
    font-size: 28px;
    margin-bottom: 30px;
  }

  .portfolio-grid {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
  }

  .portfolio-item-title {
    font-size: 1.3rem;
  }
}

@media (max-width: 480px) {
  .portfolio-grid {
    grid-template-columns: 1fr;
  }

  .portfolio-card {
    max-width: 100%;
  }

  .portfolio-overlay {
    padding: 15px;
  }

  .portfolio-item-title {
    font-size: 1.2rem;
  }

  .portfolio-description {
    -webkit-line-clamp: 2;
  }
}

.decoration-image {
  position: absolute;
  right: 0;
  bottom: -290px;
  width: 110px;
  aspect-ratio: 1.45;
  object-fit: contain;
}

.brand-section {
  flex: 1;
  font-family: Archivo, sans-serif;
  color: #090909;
  font-weight: 700;
}

.brand-tagline {
  font-size: 40px;
  margin: 20px 0 0;
}

.contact-section {
  flex: 1;
  margin-top: 9px;
  font-family: Archivo, sans-serif;
  font-size: 24px;
  color: #090909;
}

.contact-title {
  font-weight: 700;
  margin: 0;
}

.contact-email {
  display: block;
  color: #090909;
  font-style: italic;
  font-weight: 500;
  margin-top: 24px;
  text-decoration: none;
}

.contact-phone {
  font-weight: 500;
  margin: 14px 0 0;
}

.contact-address {
  margin: 20px 0 0;
  font-style: normal;
}

.news-section {
  flex: 1;
}

.news-title {
  color: #090909;
  font-family: Archivo, sans-serif;
  font-size: 24px;
  font-weight: 700;
  margin: 0;
}

.news-images {
  display: flex;
  margin-top: 41px;
  gap: 20px;
  justify-content: space-between;
}

.news-image {
  width: 68px;
  aspect-ratio: 1;
  object-fit: contain;
}

.loading-state,
.error-state {
  text-align: center;
  padding: 2rem;
  font-size: 1.2rem;
  color: #AE7A42;
}

@media (max-width: 991px) {

  .brand-title {
    font-size: 40px;
  }

  .main-content {
    margin-top: 40px;
  }

  .content-grid {
    flex-direction: column;
  }

  .left-column,
  .right-column {
    width: 100%;
  }

  .profile-name {
    font-size: 40px;
    padding: 0 20px 40px;
  }

  .profile-info {
    flex-direction: column;
  }

  .profile-picture {
    width: 100%;
    max-width: 300px;
  }

  .info-details {
    margin-top: 40px;
  }

  .request-button {
    margin-top: 40px;
    width: 100%;
  }

  .about-section {
    padding: 20px;
  }

  .recommendations-sidebar {
    padding: 20px;
    margin-top: 40px;
  }

  .portfolio-content {
    padding: 20px;
  }

  .portfolio-grid {
    flex-direction: column;
  }

  .portfolio-card {
    width: 100%;
    margin-top: 31px;
  }

  .portfolio-card:first-child {
    margin-top: 0;
  }


  .contact-section,
  .news-section {
    margin-top: 40px;
  }

  .news-images {
    margin-top: 40px;
    flex-wrap: wrap;
    justify-content: center;
  }
}
</style>