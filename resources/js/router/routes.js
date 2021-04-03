// Lazy load các trang bằng cách () => import('')

export default [
    {
        path: '/backend',
        name: 'backend',
        component: () => import('../pages/Backend/BackendIndex'),
        children: [
            {
                path: 'dashboard',
                name: 'dashboard',
                component: () => import('../pages/Backend/Dashboard/DashboardIndex')
            },
            {
                path: 'user',
                name: 'user',
                component: () => import('../pages/Backend/User/UserIndex')
            },
            {
                path: 'app',
                name: 'app',
                component: () => import('../pages/Backend/App/AppIndex')
            },
            {
                path: 'permission',
                name: 'permission',
                component: () => import('../pages/Backend/Permission/PermissionIndex')
            },
            {
                path: 'role',
                name: 'role',
                component: () => import('../pages/Backend/Role/RoleIndex')
            },
            {
                path: 'chat',
                name: 'chat',
                component: () => import('../pages/Backend/Chat/ChatIndex')
            },
            {
                path: 'chat-rtc',
                name: 'chatRtc',
                component: () => import('../pages/Backend/ChatRtc/ChatRtcIndex')
            },
            {
                path: 'notification',
                name: 'notification',
                component: () => import('../pages/Backend/Notification/NotificationIndex')
            },

            {
                path: 'component-demo',
                name: 'componentDemo',
                component: () => import('~/pages/Backend/ComponentDemo/ComponentDemoIndex'),
                children: [
                    {
                        path: 'demo-auto-complete',
                        name: 'demoAutoComplete',
                        component: () => import('~/pages/Backend/ComponentDemo/AutoComplete/AutoCompleteIndex')
                    },
                    {
                        path: 'demo-combo-box',
                        name: 'demoComboBox',
                        component: () => import('~/pages/Backend/ComponentDemo/ComboBox/ComboBoxIndex')
                    },
                    {
                        path: 'demo-tags-input',
                        name: 'demoTagsInput',
                        component: () => import('~/pages/Backend/ComponentDemo/TagsInput/TagsInputIndex')
                    }
                ]
            },

            {
                path: 'content',
                name: 'content',
                component: () => import('~/pages/Backend/Content/ContentIndex')
            },

            {
                path: 'config',
                name: 'config',
                component: () => import('~/pages/Backend/Config/ConfigIndex')
            },

            {
                path: 'country',
                name: 'country',
                component: () => import('~/pages/Backend/Country/CountryIndex')
            },
            {
                path: 'category/:category',
                name: 'category',
                component: () => import('~/pages/Backend/Category/CategoryIndex')
            },

            {
                path: 'organization',
                name: 'organization',
                component: () => import('~/pages/Backend/Organization/OrganizationIndex'),
                meta: {
                    permission: 'admin'
                }
            },
            {
                path: 'system-log',
                name: 'systemLog',
                component: () => import('../pages/Backend/SystemLog/SystemLogIndex')
            },
            {
                path: 'log-file',
                name: 'logFile',
                component: () => import('../pages/Backend/LogFile/LogFileIndex')
            },
            {
                path: 'province',
                name: 'province',
                component: () => import('~/pages/Backend/Province/ProvinceIndex')
            },
            {
                path: 'district',
                name: 'district',
                component: () => import('~/pages/Backend/District/DistrictIndex')
            },
            {
                path: 'commune',
                name: 'commune',
                component: () => import('~/pages/Backend/Commune/CommuneIndex')
            },
            {
                path: 'slide',
                name: 'slide',
                component: () => import('~/pages/Backend/Slide/SlideIndex')
            }
        ]
    },
    {
        path: '*',
        name: 'NotFound',
        component: () => import('../pages/NotFound/NotFoundIndex')
    }
];
