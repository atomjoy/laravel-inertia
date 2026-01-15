<script setup lang="ts" generic="TData">
import type { Table } from '@tanstack/vue-table'
import { Button } from '@/components/ui/button'

interface DataTablePaginationProps {
  table: Table<TData>
}
defineProps<DataTablePaginationProps>()
</script>

<template>
  <div class="flex items-center justify-end p-5 space-x-2">
    <Button
      variant="outline"
      size="sm"
      :disabled="!table.getCanPreviousPage()"
      @click="table.previousPage()"
    >Previous</Button>

    <Button
      variant="outline"
      size="sm"
      :disabled="!table.getCanNextPage()"
      @click="table.nextPage()"
    >Next</Button>
  </div>
</template>
