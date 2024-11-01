import {h} from "vue";
import UserActions from "@/Pages/Users/UserActions.vue";

const UserColumns = [
    {
        accessorKey: 'row_number',
        header: '#',
        cell: ({ row }) => h('div', null, row.getValue('row_number')),
    },
    {
        accessorKey: 'fullname',
        header: 'Full Name',
        cell: ({ row }) => h('div', null, row.getValue('fullname')),
    },
    {
        accessorKey: 'username',
        header: 'Username',
        cell: ({ row }) => h('div', null, row.getValue('username')),
    },
    {
        accessorKey: 'email',
        header: 'Email',
        cell: ({ row }) => h('div', null, row.getValue('email')),
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const user = row.original

            return h('div', { class: 'relative' }, h(UserActions, {
                user,
            }))
        },
    },
]

export default UserColumns
