import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import CategoryController from '@/actions/App/Http/Controllers/CategoryController';
import DiscussionController from '@/actions/App/Http/Controllers/DiscussionController';
import type { BreadcrumbItem } from '@/types';

interface Category {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    discussions_count: number;
}

interface CategoriesPageProps {
    categories: Category[];
}

const getDiscussionText = (count: number): string => {
    return count === 1 ? 'discussion' : 'discussions';
};

const gridClasses =
    'grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: CategoryController.index().url,
    },
];

export default function Index({ categories }: CategoriesPageProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Categories" />
            {categories.length === 0 ? (
                <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                    <div className="rounded-xl border border-sidebar-border/70 bg-white p-8 dark:border-sidebar-border dark:bg-gray-800">
                        <h1 className="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                            Categories
                        </h1>
                        <p className="text-gray-600 dark:text-gray-300">
                            No categories available yet.
                        </p>
                    </div>
                </div>
            ) : (
                <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                    <div className="rounded-xl border border-sidebar-border/70 bg-white p-8 dark:border-sidebar-border dark:bg-gray-800">
                        <div className="mb-6 flex items-center justify-between">
                            <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
                                Categories
                            </h1>
                            <Link
                                href={DiscussionController.create().url}
                                className="focus:ring-opacity-50 rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            >
                                Create Discussion
                            </Link>
                        </div>

                        <div className={gridClasses}>
                            {categories.map((category) => (
                                <Link
                                    key={category.id}
                                    href={
                                        CategoryController.show({
                                            category: category.slug,
                                        }).url
                                    }
                                    className="group block rounded-lg border border-gray-200 p-6 transition-colors duration-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700"
                                >
                                    <div className="mb-4 flex items-center justify-between">
                                        <h3 className="text-lg font-semibold text-gray-900 group-hover:text-blue-600 dark:text-white dark:group-hover:text-blue-400">
                                            {category.name}
                                        </h3>
                                        <span className="text-sm text-gray-500 dark:text-gray-400">
                                            {category.discussions_count}{' '}
                                            {getDiscussionText(
                                                category.discussions_count,
                                            )}
                                        </span>
                                    </div>

                                    {category.description && (
                                        <p className="text-sm text-gray-600 dark:text-gray-300">
                                            {category.description}
                                        </p>
                                    )}
                                </Link>
                            ))}
                        </div>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
