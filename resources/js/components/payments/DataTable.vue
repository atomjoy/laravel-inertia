<script setup lang="ts" generic="TData, TValue">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { valueUpdater } from '@/components/ui/table/utils';
import type { ColumnDef, PaginationState, RowSelectionState } from '@tanstack/vue-table';
import { ColumnFiltersState, FlexRender, getCoreRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, SortingState, useVueTable } from '@tanstack/vue-table';
import { ChevronDown } from 'lucide-vue-next';
import { ref } from 'vue';
import DataTablePagination from './DataTablePagination.vue';
import Filter from './Filter.vue';
import { filter_status } from './types';

const props = defineProps<{
	columns: ColumnDef<TData, TValue>[];
	data: TData[];
}>();

const filter_toolbar = [filter_status];
const columnFilters = ref<ColumnFiltersState>([]);
const rowSelection = ref<RowSelectionState>({});
const sorting = ref<SortingState>([
	{
		id: 'id',
		desc: true, // sort by age in descending order by default
	},
]);

const table = useVueTable({
	enableRowSelection: true,
	get data() {
		return props.data;
	},
	get columns() {
		return props.columns;
	},
	state: {
		get sorting() {
			return sorting.value;
		},
		get rowSelection() {
			return rowSelection.value;
		},
		get columnFilters() {
			return columnFilters.value;
		},
	},
	initialState: {
		pagination: {
			pageIndex: 0,
			pageSize: 10
		}
	},
	// @ts-ignore
	getRowId: (row) => row.id,
	getCoreRowModel: getCoreRowModel(),
	getPaginationRowModel: getPaginationRowModel(),
	getFilteredRowModel: getFilteredRowModel(),
	getSortedRowModel: getSortedRowModel(),
	onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
	onRowSelectionChange: (updaterOrValue) => valueUpdater(updaterOrValue, rowSelection),
	onColumnFiltersChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnFilters),
});
</script>

<template>
	<div class="space-y-4">
		<div class="rounded-md border" v-if="sorting">{{ sorting }} {{ rowSelection }} {{ columnFilters }}</div>

		<div class="flex items-center py-4">
			<Input class="h-9 max-w-sm" placeholder="Filter emails..." :modelValue="table.getColumn('email')?.getFilterValue() as string" @update:modelValue="table.getColumn('email')?.setFilterValue($event)" />

			<div v-for="filter in filter_toolbar" :key="filter.title">
				<Filter :column="table.getColumn(filter.column)" :title="filter.title" :options="filter.data"></Filter>
			</div>

			<DropdownMenu>
				<DropdownMenuTrigger as-child>
					<Button variant="outline" class="ml-auto"> Columns <ChevronDown class="ml-2 h-4 w-4" /> </Button>
				</DropdownMenuTrigger>

				<DropdownMenuContent align="end">
					<DropdownMenuCheckboxItem
						v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
						:key="column.id"
						class="capitalize"
						:modelValue="column.getIsVisible()"
						@update:modelValue="
							(value: any) => {
								column.toggleVisibility(!!value);
							}
						"
					>
						{{ column.id }}
					</DropdownMenuCheckboxItem>
				</DropdownMenuContent>
			</DropdownMenu>
		</div>

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
