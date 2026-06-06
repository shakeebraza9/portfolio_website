  <template>
    <v-container id="projects" fluid class="py-16 bg-background position-relative min-vh-100">
      <div class="projects-glow"></div>

      <v-container>
        <v-row class="mb-10" align="center">
          <v-col cols="12" md="6">
            <span class="text-primary font-weight-bold text-uppercase letter-spacing-2">My Portfolio</span>
            <h2 class="text-h3 font-weight-black text-white mt-2">Latest <span class="text-primary">Projects</span></h2>
            <p class="text-light_text_on mt-4">
              Kuch aise projects jo maine Laravel, Vue.js aur Python use karke build kiye hain.
            </p>
          </v-col>
          
          <v-col cols="12" md="6" class="d-flex justify-md-end">
            <v-tabs
              v-model="activeTab"
              bg-color="transparent"
              color="primary"
              class="filter-tabs"
              density="comfortable"
              @update:model-value="fetchProjects(1)"
            >
              <!-- <v-tab value="all" class="text-none">All</v-tab>
              <v-tab value="Laravel" class="text-none">Laravel</v-tab>
              <v-tab value="Vue" class="text-none">Vue.js</v-tab>
              <v-tab value="Scraping" class="text-none">Scraping</v-tab> -->
            </v-tabs>
          </v-col>
        </v-row>

        <v-row v-if="!loading">
          <v-col 
            v-for="project in projects" 
            :key="project.id" 
            cols="12" md="4" sm="6"
            class="project-col"
          >
            <v-hover v-slot="{ isHovering, props }">
              <v-card 
                v-bind="props"
                class="project-card h-full d-flex flex-column" 
                :elevation="isHovering ? 12 : 2"
                border="border"
                color="surface"
              >
                <div class="image-container overflow-hidden">
                  <v-img 
                    :src="project.image" 
                    height="250" 
                    contain
                    :class="{ 'zoom-effect': isHovering }"
                  >
                    <v-fade-transition grow>
                      <div v-if="isHovering" class="d-flex align-center justify-center fill-height bg-black-opacity-60">
                        <v-btn :to="`/project/${project.slug}`"  color="white" variant="outlined" rounded="pill" class="mx-2 bg-background">Details</v-btn>
                        <v-btn v-if="project.url" bg-black color="white" icon="mdi-launch" variant="flat" :href="project.url" target="_blank"></v-btn>
                      </div>
                    </v-fade-transition>
                  </v-img>
                </div>

                <v-card-text class="pa-6 flex-grow-1">
                  <div class="mb-3">
                    <v-chip 
                      v-for="tag in formatTags(project.tag)" 
                      :key="tag"
                      size="x-small" 
                      variant="tonal" 
                      color="primary" 
                      class="mr-1 mb-1 font-weight-bold"
                    >
                      {{ tag }}
                    </v-chip>
                  </div>

                  <h3 class="text-h5 font-weight-bold text-white mb-2">{{ project.name }}</h3>
                  <div class="text-light_text_on text-body-2 line-clamp-3" v-html="project.description"></div>
                </v-card-text>

                <v-divider style="opacity: 0.1"></v-divider>

                <v-card-actions class="pa-4">
                  <v-btn v-if="project.github_link" icon="mdi-github" variant="text" color="white" :href="project.github_link" target="_blank"></v-btn>
                  <v-spacer></v-spacer>
                  <v-btn :to="`/project/${project.slug}`" variant="text" color="primary" append-icon="mdi-arrow-right" class="font-weight-bold">
                    View Case
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-hover>
          </v-col>
        </v-row>

        <v-row v-else>
          <v-col v-for="n in 3" :key="n" cols="12" md="4" sm="6">
            <v-skeleton-loader type="card, list-item-two-line" bg-color="surface"></v-skeleton-loader>
          </v-col>
        </v-row>

        <v-row class="mt-12" justify="center">
          <v-pagination
            v-model="page"
            :length="totalPages"
            :total-visible="5"
            color="primary"
            @update:model-value="fetchProjects"
          ></v-pagination>
        </v-row>
      </v-container>
    </v-container>
  </template>

  <script>
  import General from '@/models/general.model';

  export default {
    data() {
      return {
        activeTab: 'all',
        projects: [],
        loading: false,
        page: 1,
        totalPages: 1,
        itemsPerPage: 6
      }
    },
    methods: {
      async fetchProjects(newPage = null) {
        if (newPage) this.page = newPage;
        
        this.loading = true;
        try {

          const params = {
            page: this.page,
            category: this.activeTab !== 'all' ? this.activeTab : null,
            limit: this.itemsPerPage
          };

          const response = await General.get('project', { params });

          this.projects = response.data; 
          this.totalPages = response.last_page;

          window.scrollTo({ top: 0, behavior: 'smooth' });
        } catch (error) {
          console.error("Error fetching projects:", error);
        } finally {
          this.loading = false;
        }
      },
      formatTags(tags) {
        if (!tags) return [];
        return typeof tags === 'string' ? tags.split(',') : tags;
      }
    },
    mounted() {
      this.fetchProjects();
    }
  }
  </script>

  <style scoped>
  .letter-spacing-2 { letter-spacing: 2px; }
  .bg-primary-darken-4-opacity-80 { background: rgba(0, 128, 255, 0.85); }

  .project-card {
    transition: transform 0.3s ease;
    border-radius: 20px !important;
    display: flex;
    flex-direction: column;
  }

  .image-container {
    overflow: hidden;
    border-radius: 20px 20px 0 0;
  }

  .zoom-effect {
    transform: scale(1.1);
    transition: transform 0.5s ease;
  }

  .projects-glow {
    position: absolute;
    top: 20%;
    left: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(0, 128, 255, 0.1) 0%, transparent 70%);
    pointer-events: none;
  }

  .line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;  
    overflow: hidden;
  }

  .filter-tabs :deep(.v-btn) {
    font-weight: bold !important;
  }
  </style>