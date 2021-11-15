const routes = [
    {
        path: "/login",
        name: "Login",
        component: () => import("../pages/public/Login"),
    },
    {
        path: "/",
        name: "Main",
        component: () => import("../pages/main/Index"),
        meta: {
            auth: true,
        },
        children: [
            {
                name: "Product",
                path: "product",
                component: () => import("../components/Product/Index"),
                meta: {
                    admin: true,
                },
            },
            {
                name: "Product Form",
                path: "product-form",
                component: () => import("../components/Product/Form"),
                meta: {
                    admin: true,
                },
            },
        ],
    },
    {
        path: "/*",
        component: () => import("../pages/main/Index"),
    },
];

export default routes;
