<script setup lang="ts" generic="TData, TValue">
import { Button } from '@/components/ui/button';
import type { Table } from '@tanstack/vue-table';

import {
	ChevronLeft,
	ChevronRight,
	ChevronsLeft,
	ChevronsRight,
} from 'lucide-vue-next';

import {
	Select,
	SelectContent,
	SelectItem,
	SelectTrigger,
	SelectValue,
} from '@/components/ui/select';

interface LastPage {
	last_page: number
}

interface DataTablePaginationProps extends LastPage {
	table: Table<TData>
}

const props = defineProps<DataTablePaginationProps>();
const pageSizes = [5, 10, 15, 25, 50];
</script>

<template>
	<div class="flex items-center justify-between mt-4">
		<div class="flex-1 text-left text-sm text-muted-foreground">
			{{ table.getFilteredSelectedRowModel().rows.length }} of
			{{ table.getFilteredRowModel().rows.length }} row(s) selected.
		</div>
		<div class="flex items-center space-x-6 lg:space-x-8">
			<div class="flex items-center space-x-2">
				<p class="text-sm font-medium">Rows per page</p>
				<Select
					:modelValue="`${table.getState().pagination.pageSize}`"
					@update:modelValue="table.setPageSize as any"
				>
					<SelectTrigger class="h-8 w-17.5">
						<SelectValue
							:placeholder="`${table.getState().pagination.pageSize}`"
						/>
					</SelectTrigger>
					<SelectContent side="top">
						<SelectItem
							v-for="pageSize in pageSizes"
							:key="pageSize"
							:value="`${pageSize}`"
						>
							{{ pageSize }}
						</SelectItem>
					</SelectContent>
				</Select>
			</div>
			<div class="flex text-sm font-medium">
				Page {{ props.table.getState().pagination.pageIndex + 1 }} of {{ props.last_page }}
			</div>
			<div class="flex items-center space-x-2">
				<Button
					variant="outline"
					class="hidden h-8 w-8 p-0 lg:flex"
					:disabled="!table.getCanPreviousPage()"
					@click="table.setPageIndex(0)"
				>
					<span class="sr-only">Go to first page</span>
					<ChevronsLeft class="h-4 w-4" />
				</Button>
				<Button
					variant="outline"
					class="h-8 w-8 p-0"
					:disabled="!table.getCanPreviousPage()"
					@click="table.previousPage()"
				>
					<span class="sr-only">Go to previous page</span>
					<ChevronLeft class="h-4 w-4" />
				</Button>
				<Button
					variant="outline"
					class="h-8 w-8 p-0"
					:disabled="props.table.getState().pagination.pageIndex + 1 >= props.last_page"
					@click="table.nextPage()"
				>
					<span class="sr-only">Go to next page</span>
					<ChevronRight class="h-4 w-4" />
				</Button>
				<Button
					variant="outline"
					class="hidden h-8 w-8 p-0 lg:flex"
					:disabled="props.table.getState().pagination.pageIndex + 1 >= props.last_page"
					@click="table.setPageIndex(table.getPageCount() - 1)"
				>
					<span class="sr-only">Go to last page</span>
					<ChevronsRight class="h-4 w-4" />
				</Button>
			</div>
		</div>
	</div>

	<!-- <div class="paginate">
    <Button @click="table.previousPage()" :disabled="!table.getCanPreviousPage()">Prev</Button>
    <Button @click="table.nextPage()" :disabled="!table.getCanNextPage()">Next</Button>
  </div> -->
</template>
