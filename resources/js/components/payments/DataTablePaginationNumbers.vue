<script setup lang="ts" generic="TData, T">
import { Button } from '@/components/ui/button';
import type { RowSelectionState, Table } from '@tanstack/vue-table';
import {
	ChevronLeft,
	ChevronRight,
	ChevronsLeft,
	ChevronsRight,
} from 'lucide-vue-next';
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination'
import {
	Select,
	SelectContent,
	SelectItem,
	SelectTrigger,
	SelectValue,
} from '@/components/ui/select';

const pageSizes = [10, 15, 25, 50, 100];

const props = defineProps<{
	total: number,
	lastPage: number,
	table: Table<TData>,
	selectedRows: number,
}>()
</script>

<template>
<div class="flex flex-col items-center mt-4 lg:flex-row gap-2">
	<div class="flex-1 text-left text-sm text-muted-foreground">
		{{ table.getFilteredSelectedRowModel().rows.length }} of {{ table.getFilteredRowModel().rows.length }} page row(s) selected.
		<span v-if="props.selectedRows > 0">({{ props.selectedRows ?? 0 }} of {{ props.total }} total row(s) selected).</span>
	</div>
	<div class="flex items-center space-x-6 lg:space-x-8">
  		<div class="flex flex-col gap-6">
			<Pagination v-slot="{ page }" :sibling-count="2" :page="props.table.getState().pagination.pageIndex + 1" :items-per-page="props.table.getState().pagination.pageSize" :total="props.total" :default-page="props.table.getState().pagination.pageIndex + 1">
			<PaginationContent v-slot="{ items }">
				<div class="flex items-center space-x-2 mr-2">
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

				<div class="h-9 text-sm font-medium px-2 py-2 mx-2">
					Page {{ props.table.getState().pagination.pageIndex + 1 }} of {{ props.lastPage }}
				</div>

				<Button
					variant="outline"
					class="hidden h-9 w-9 p-0 lg:flex"
					:disabled="!table.getCanPreviousPage()"
					@click="table.setPageIndex(0)"
				>
					<span class="sr-only">Go to first page</span>
					<ChevronsLeft class="h-4 w-4" />
				</Button>
				<Button
					variant="outline"
					class="h-9 w-9 p-0"
					:disabled="!table.getCanPreviousPage()"
					@click="table.previousPage()"
				>
					<span class="sr-only">Go to previous page</span>
					<ChevronLeft class="h-4 w-4" />
				</Button>

				<!-- <PaginationPrevious
					:disabled="!table.getCanPreviousPage()"
					@click="table.previousPage()"
				/> -->

				<template v-for="(item, index) in items" :key="index">
					<PaginationItem
						v-if="item.type === 'page'"
						:value="item.value"
						:is-active="item.value === page"
						@click="table.setPageIndex(item.value - 1)"
						class="h-9 w-9 p-0"
					>
						{{ item.value }}
					</PaginationItem>
					<PaginationEllipsis v-else :index="index"/>
				</template>


				<!-- <PaginationNext
					:disabled="!table.getCanNextPage()"
					@click="table.nextPage()"
				/> -->

				<Button
					variant="outline"
					class="h-9 w-9 p-0"
					:disabled="props.table.getState().pagination.pageIndex + 1 >= props.lastPage"
					@click="table.nextPage()"
				>
					<span class="sr-only">Go to next page</span>
					<ChevronRight class="h-4 w-4" />
				</Button>
				<Button
					variant="outline"
					class="hidden h-9 w-9 p-0 lg:flex"
					:disabled="props.table.getState().pagination.pageIndex + 1 >= props.lastPage"
					@click="table.setPageIndex(props.lastPage - 1)"
				>
					<span class="sr-only">Go to last page</span>
					<ChevronsRight class="h-4 w-4" />
				</Button>
			</PaginationContent>
			</Pagination>
		</div>
	</div>
</div>
</template>
