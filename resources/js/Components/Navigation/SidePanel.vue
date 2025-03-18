<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { ref, onMounted, useTemplateRef } from "vue";
import { route } from "ziggy-js";

const patientMenu = useTemplateRef("patient-menu");
const inventoryMenu = useTemplateRef("inventory-menu");
const labMenu = useTemplateRef("lab-menu");
const activeMenuClass = "menu__dropdown--active";

const page = usePage();

const isCurrentRoute = (routeName) => {
  const routeUrl = routeName;
  return page.component === routeUrl;
};

const toggleSubMenu = (e) => {
  const menu = e.target;

  if (menu.classList.contains(activeMenuClass)) {
    menu.classList.remove(activeMenuClass);
  } else {
    menu.classList.add(activeMenuClass);
  }
};

onMounted(() => {
  if (page.component.startsWith("Patient")) {
    patientMenu.value.classList.add(activeMenuClass);
  } else if (page.component.startsWith("Inventory")) {
    inventoryMenu.value.classList.add(activeMenuClass);
  } else if (page.component.startsWith("Lab")) {
    labMenu.value.classList.add(activeMenuClass);
  }
});
</script>

<template>
  <aside class="side-panel fixed top-0 bottom-0 bg-main-gray z-10">
    <div class="text-center w-full md:py-[1.5rem] py-[1rem]">
      <p class="md:text-[2.5rem] text-[1.5rem] font-semibold text-white">PMS</p>
    </div>
    <ul
      class="md:my-[3.25rem] my-[2.5rem] md:px-[2rem] px-[1.25rem] text-white flex flex-col md:gap-[.25rem]"
    >
      <li>
        <Link class="menu" href="/" :class="{ 'menu--active': isCurrentRoute('Home') }"
          >Dashboard</Link
        >
      </li>

      <li>
        <a href="#" ref="patient-menu" class="menu menu__dropdown" @click="toggleSubMenu"
          >Patients</a
        >
        <ul class="sub-menu flex flex-col mt-[.5rem] gap-[.5rem]">
          <li>
            <Link
              :class="{ active: isCurrentRoute('Patients/PatientsList') }"
              :href="route('patients.index')"
              >View Patients</Link
            >
          </li>
          <li>
            <Link
              :class="{ active: isCurrentRoute('Patients/Create') }"
              :href="route('patients.create')"
              >Add New Patient</Link
            >
          </li>
          <li>
            <Link
              :class="{ active: isCurrentRoute('PatientVisits/Index') }"
              :href="route('patient-visits.index')"
              >Patient Transactions</Link
            >
          </li>
        </ul>
      </li>

      <li>
        <a
          href="#"
          ref="inventory-menu"
          class="menu menu__dropdown"
          @click="toggleSubMenu"
          >Inventory</a
        >
        <ul class="sub-menu flex flex-col mt-[.5rem] gap-[.5rem]">
          <li>
            <Link
              :class="{ active: isCurrentRoute('Inventory/InventoryList') }"
              :href="route('inventory.index')"
              >View Inventory</Link
            >
          </li>
          <li>
            <Link
              :class="{ active: isCurrentRoute('Inventory/Create') }"
              :href="route('inventory.create')"
              >Add New Inventory</Link
            >
          </li>
          <li>
            <Link
              :class="{
                active: isCurrentRoute('InventoryCategory/InventoryCategoryList'),
              }"
              :href="route('inventory-categories.index')"
              >Categories</Link
            >
          </li>
        </ul>
      </li>

      <li>
        <a href="#" ref="lab-menu" class="menu menu__dropdown" @click="toggleSubMenu"
          >Lab Tests</a
        >
        <ul class="sub-menu flex flex-col mt-[.5rem] gap-[.5rem]">
          <li>
            <Link
              :class="{ active: isCurrentRoute('LabTests/LabTestsList') }"
              :href="route('lab-tests.index')"
              >View Lab Tests</Link
            >
          </li>
          <li>
            <Link
              :class="{ active: isCurrentRoute('LabTests/Create') }"
              :href="route('lab-tests.create')"
              >Add New Lab Test</Link
            >
          </li>
        </ul>
      </li>

      <li>
        <a :href="route('reports.index')" ref="reports" class="menu" @click="toggleSubMenu"
          >Reports</a
        >
        <!-- <ul class="sub-menu flex flex-col mt-[.5rem] gap-[.5rem]">
          <li>
            <Link
              :class="{ active: isCurrentRoute('LabTests/LabTestsList') }"
              :href="route('lab-tests.index')"
              >View Lab Tests</Link
            >
          </li>
        </ul> -->
      </li>
    </ul>
  </aside>
</template>

<style lang="scss" scoped>
.side-panel {
  width: 16.5rem;
  max-width: 16.5rem;
  left: -100%;
  transition: 0.35s ease-in-out left;

  &--active {
    left: 0;
  }

  @media only screen and (min-width: 768px) {
    left: 0;
  }

  .sub-menu {
    display: none;
    li a {
      display: block;
      padding: 0.75rem 0.9rem;
      font-size: 0.9rem;
      padding-left: 1.5rem;
      cursor: pointer;
      &:hover,
      &.active {
        background: rgba(128, 128, 128, 0.1);
        border-radius: 5px;
      }
    }
  }

  .menu {
    position: relative;
    display: block;
    cursor: pointer;
    padding: 0.75rem 0.9rem;

    &:hover {
      background: rgba(128, 128, 128, 0.1);
      border-radius: 5px;
    }

    &--active {
      background: rgba(128, 128, 128, 0.1);
      border-radius: 5px;
    }

    &__dropdown {
      margin: 0.5rem 0;
      &::after {
        content: "";
        background: url("/icons/arrow-down.png");
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%) rotate(-90deg);
        transition: 0.25s ease-in-out transform;
        width: 12px;
        height: 8px;
        display: block;
        background-repeat: no-repeat;
      }

      &--active {
        background: rgba(128, 128, 128, 0.1);
        border-radius: 5px;

        &::after {
          content: "";
          transform: translateY(-50%) rotate(0deg);
        }

        & + .sub-menu {
          display: flex;
        }
      }
    }
  }
}
</style>
