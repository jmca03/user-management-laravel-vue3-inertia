<script setup>

import {ref, toRaw, watch} from "vue";
import {
    FlexRender,
    getCoreRowModel, getExpandedRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable
} from "@tanstack/vue-table";
import {Table} from '@/Components/ui/table'
import {Button} from '@/Components/ui/button'
import {TableBody, TableCell, TableHead, TableHeader, TableRow} from "@/Components/ui/table/index.js";
import {valueUpdater} from "@/lib/utils.js"
import {Link} from '@inertiajs/vue3';

const props = defineProps({
                              data   : {
                                  type    : Object,
                                  required: true,
                              },
                              columns: {
                                  type    : Array,
                                  required: true,
                              }
                          })

const sorting = ref([])
const columnFilters = ref([])
const columnVisibility = ref({})
const rowSelection = ref({})
const expanded = ref({})

const table = useVueTable({
                              data                    : props?.data?.data || [],
                              columns                 : props?.columns || [],
                              getCoreRowModel         : getCoreRowModel(),
                              getPaginationRowModel   : getPaginationRowModel(),
                              getSortedRowModel       : getSortedRowModel(),
                              getFilteredRowModel     : getFilteredRowModel(),
                              getExpandedRowModel     : getExpandedRowModel(),
                              onSortingChange         : updaterOrValue => valueUpdater(updaterOrValue, sorting),
                              onColumnFiltersChange   : updaterOrValue => valueUpdater(updaterOrValue, columnFilters),
                              onColumnVisibilityChange: updaterOrValue => valueUpdater(updaterOrValue, columnVisibility),
                              onRowSelectionChange    : updaterOrValue => valueUpdater(updaterOrValue, rowSelection),
                              onExpandedChange        : updaterOrValue => valueUpdater(updaterOrValue, expanded),
                              manualPagination        : true,
                              pageCount: props?.data?.last_page,
                              state                   : {
                                  get sorting() {
                                      return sorting.value
                                  },
                                  get columnFilters() {
                                      return columnFilters.value
                                  },
                                  get columnVisibility() {
                                      return columnVisibility.value
                                  },
                                  get rowSelection() {
                                      return rowSelection.value
                                  },
                                  get expanded() {
                                      return expanded.value
                                  },
                                  pagination: {
                                      pageIndex: props?.data?.current_page || 0,
                                      pageSize : props?.data?.per_page
                                  }
                              },
                          })

</script>

<template>
    <div class="rounded-md border">
        <Table>
            <TableHeader>
                <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                    <TableHead v-for="header in headerGroup.headers" :key="header.id">
                        <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header"
                                    :props="header.getContext()"/>
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <template v-if="table.getRowModel().rows?.length">
                    <template v-for="row in table.getRowModel().rows" :key="row.id">
                        <TableRow :data-state="row.getIsSelected() && 'selected'">
                            <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()"/>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="row.getIsExpanded()">
                            <TableCell :colspan="row.getAllCells().length">
                                {{ JSON.stringify(row.original) }}
                            </TableCell>
                        </TableRow>
                    </template>
                </template>

                <TableRow v-else>
                    <TableCell
                        :colspan="columns.length"
                        class="h-24 text-center"
                    >
                        No results.
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>

    <div class="flex items-center justify-end space-x-2 py-4">
        <!--        <div class="flex-1 text-sm text-muted-foreground">-->
        <!--            {{ table.getFilteredSelectedRowModel().rows.length }} of-->
        <!--            {{ table.getFilteredRowModel().rows.length }} row(s) selected.-->
        <!--        </div>-->
        <div class="space-x-2">
            <Button
                variant="outline"
                size="sm"
                :disabled="!table.getCanPreviousPage()"
                @click="table.previousPage()"
            >
                <Link :href="props?.data?.prev_page_url">
                    Previous
                </Link>
            </Button>
            <Button
                variant="outline"
                size="sm"
                :disabled="!table.getCanNextPage()"
                @click="table.nextPage()"
            >
                <Link :href="props?.data?.next_page_url">
                    Next
                </Link>
            </Button>
        </div>
    </div>
</template>

<style scoped>

</style>
