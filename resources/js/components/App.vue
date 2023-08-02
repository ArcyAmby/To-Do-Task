<template>
  <div class="container-fluid m-0">
    <Header />
    <header>
      <div class="app-header w-auto">
        <div class="container">
          <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            
            <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
              <li>
                <button class="fancy-button home-button" @click="goHome">
                  <i class="fas fa-home"></i> Home
                </button>
              </li>
              <li>
                <button @click="showNewTaskModal" class="fancy-button new-task-button">
                  <i class="fas fa-plus"></i>
                  New Task
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>

    <div class="card">
      <div class="card-body">
        <div class="container-fluid">
          <TaskList :tasks="tasks" @task-delete="onTaskDelete" @update-tasks="onUpdateTasks" />
        </div>
      </div>
    </div>


    <div v-if="showModal" class="modal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content fancy-modal">
          <div class="modal-header text-white">
            <h3 class="modal-title">New Task</h3>
            <button type="button" class="close" @click="showModal = false">&times;</button>
          </div>
          <div class="modal-body">
            <i v-if="!isSaving" class=""></i>
            <i v-else class="fas fa-spinner fa-spin"></i>
            <TaskOption :isNewTask="true" :task="emptyTask" @task-created="createTask" @new-task-created="onNewTaskCreated"
            @task-updated="onUpdateTask" />
            
          </div>
        </div>
      </div>
    </div>
    <Footer />
  </div>
</template>

<script>
import TaskOption from './TaskOption.vue';
import TaskList from './TaskList.vue';
import axios from 'axios';

export default {
  name: 'App',
  components: {
    TaskOption,
    TaskList
  },
  data() {
    return {
      showModal: false,
      emptyTask: { 
        title: '',
        description: '',
        status: '',
        file: null
      },
      tasks: [], 
      isNewTask: true,
      selectedTask: null,
      isLoading: false, 
      isSaving: false,
    };
  },
  methods: {
    showNewTaskModal() {
      this.isNewTask = true;
      this.selectedTask = null;
      this.showModal = true;
    },

    async createTask(newTask) {
      try {
        this.isSaving = true;
        const response = await axios.post('/api/tasks', newTask);
        newTask.id = response.data.id;
        this.tasks.push(newTask);
        this.showModal = false;
        this.$emit('new-task-created', newTask);
        console.log('API Response for new task:', newTask);
      } catch (error) {
        console.error('Error creating task:', error);
      } finally {
        this.isSaving = false;
      }
    },


    async onTaskDelete(deletedTaskId) {
      try {
        await axios.delete(`/api/tasks/${deletedTaskId}`);
        console.log('Task deleted successfully');
        this.fetchTasks();
      } catch (error) {
        console.error('Error deleting task:', error);
      }
    },
    
    async fetchTasks() {
      try {
        const response = await axios.get('/api/tasks');
        console.log('API Response:', response.data);
        this.tasks = response.data;
      } catch (error) {
        console.error(error);
      }
    },

    onUpdateTask(updatedTask, taskId) {
      this.tasks = this.tasks.map((task) => (task.id === taskId ? updatedTask : task));
    },

    onUpdateTasks(updatedTasks) {
      this.tasks = updatedTasks;
    },

    onNewTaskCreated(newTask) {
      this.showModal = false;
      this.tasks.push(newTask);
    },

    editTask(task) {
      this.isNewTask = false;
      this.selectedTask = { ...task };
      this.showModal = true;
    },
    
    
  },
  emits: ['new-task-created'],
  created() {
  this.fetchTasks();
  }

}
</script>

<style>
:root {
  --primary-color: #101820FF;
  --primary-light: #40464D;
  --secondary-color: #F2AA4CFF;
}

.container-fluid {
  margin: 0;
  padding: 0;
}

.app-header {
  background-color: var(--primary-light);
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-dialog {
  max-width: 400px;
  width: 90%;
}

.modal-header {
  background-color: var(--secondary-color);
  color: #fff;
}

.modal-body {
  text-align: center;
}

.modal-footer button {
  background-color: var(--secondary-color);
  margin-right: 10px;
  border-style: none;
}
.button-container {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.fancy-button {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #fff;
  color: var(--primary-color);
  border: none;
  padding: 6px 18px;
  font-size: 14px;
  font-weight: bold;
  border-radius: 8px;
  cursor: pointer;
  margin: 10px;
  transition: background-color 0.3s ease;
}

.fancy-button i {
  margin-right: 10px;
}

.fancy-button:hover {
  background-color: #E89F36FF;
}

.design-modal {
  padding: 20px;
  border-radius: 10px;
  background-color: #ffffff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.1);
}

.modal-body {
  text-align: left;
}

 .nav li {
  margin-bottom: 10px;
}

@media (max-width: 767px) {
  .app-header {
    display: flex;
    justify-content: space-between;
  }
}

</style>
