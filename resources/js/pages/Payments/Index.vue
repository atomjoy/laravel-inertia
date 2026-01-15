<script setup lang="ts" generic="TData, TValue">
import DataTablePagination from '@/components/payments/DataTablePagination.vue';
import { columns } from '@/components/payments/columns';
import { valueUpdater } from '@/components/ui/table/utils';
import { router } from '@inertiajs/vue3';
import type { PaginationState } from '@tanstack/vue-table';
import { ref } from 'vue';
import { ColumnFiltersState, ExpandedState, FlexRender, getCoreRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, RowSelectionState, SortingState, useVueTable, VisibilityState } from '@tanstack/vue-table';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

const props = defineProps({ data: Object });
const expanded = ref<ExpandedState>({});
const rowSelection = ref<RowSelectionState>({});
const columnVisibility = ref<VisibilityState>({});
const columnFilters = ref<ColumnFiltersState>([]);
const sorting = ref<SortingState>([
	{
		id: 'id',
		desc: true, // sort by age in descending order by default
	},
]);

const pagination = ref<PaginationState>({
	pageIndex: 0,
	pageSize: 5,
});

// Update table state after on change events
// https://tanstack.com/table/latest/docs/framework/vue/guide/table-state
// https://tanstack.com/table/latest/docs/guide/pagination#should-you-use-client-side-pagination
const table = useVueTable({
	get data() {
		return props?.data?.data;
	},
	get columns() {
		return columns;
	},
	state: {
		get sorting() {
			return sorting.value;
		},
		get rowSelection() {
			return rowSelection.value;
		},
		get pagination() {
			return pagination.value;
		},
		get columnFilters() {
			return columnFilters.value;
		},
		get columnVisibility() {
			return columnVisibility.value;
		},
		get expanded() {
			return expanded.value;
		},
	},
	getRowId: (row) => row.id,
	getPaginationRowModel: getPaginationRowModel(),
	getCoreRowModel: getCoreRowModel(),
	getFilteredRowModel: getFilteredRowModel(),
	getSortedRowModel: getSortedRowModel(),
	onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
	onColumnFiltersChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnFilters),
	onColumnVisibilityChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnVisibility),
	onRowSelectionChange: (updaterOrValue) => valueUpdater(updaterOrValue, rowSelection),
	onExpandedChange: (updaterOrValue) => valueUpdater(updaterOrValue, expanded),
	onPaginationChange: (updaterOrValue) => {
		pagination.value = typeof updaterOrValue === 'function' ? updaterOrValue(pagination.value) : updaterOrValue;

		// Update backend here
		router.get(
			'payments',
			{
				page: pagination.value.pageIndex + 1,
				per_page: pagination.value.pageSize,
				sort_field: sorting.value[0]?.id,
				sort_direction: sorting.value.length == 0 ? undefined : sorting.value[0]?.desc ? 'desc' : 'asc',
			},
			{ preserveState: true, preserveScroll: true },
		);
	},
	rowCount: props.data?.total ?? 0, // rowCount
	manualPagination: true,
	manualSorting: true,
	manualFiltering: true,
	enableRowSelection: true,
	// enableRowSelection: row => row.original.age > 18, //only enable row selection for adults
	// enableMultiRowSelection: false, //only allow a single row to be selected at once
});

function copy(id: string) {
	navigator.clipboard.writeText(id);
}
</script>

<template>
	<div class="space-y-4">
		<!-- <div class="rounded-md border" v-if="sorting">{{ sorting }} {{ pagination }} {{ rowSelection }}</div> -->
		<div class="rounded-md border">
			<Table>
				<TableHeader>
					<TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
						<TableHead v-for="header in headerGroup.headers" :key="header.id">
							<FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
						</TableHead>
					</TableRow>
				</TableHeader>

				<TableBody>
					<template v-if="table.getRowModel().rows?.length">
						<TableRow v-for="row in table.getRowModel().rows" :key="row.id" :data-state="row.getIsSelected() ? 'selected' : undefined">
							<TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
								<FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
							</TableCell>
						</TableRow>
					</template>
					<template v-else>
						<TableRow>
							<TableCell :colspan="columns.length" class="h-24 text-center"> No results. </TableCell>
						</TableRow>
					</template>
				</TableBody>
			</Table>
		</div>

		<DataTablePagination :table="table" />
	</div>
</template>

<style>
.payment-status {
	float: left;
	padding: 5px 10px;
	border-radius: 50px;
	background: #f9f9f9;
	color: #222;
}
</style>
