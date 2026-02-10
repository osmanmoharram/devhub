import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import DiscussionController from '@/actions/App/Http/Controllers/DiscussionController';
import CategoryController from '@/actions/App/Http/Controllers/CategoryController';
import type { BreadcrumbItem } from '@/types';

interface Category {
    id: number;
    name: string;
    slug: string;
}

interface CreateDiscussionPageProps {
    categories: Category[];
    preselectedCategory?: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: CategoryController.index().url,
    },
    {
        title: 'Create Discussion',
        href: DiscussionController.create().url,
    },
];

export default function Create({
    categories,
    preselectedCategory,
}: CreateDiscussionPageProps) {
    const { data, setData, post, processing, errors, recentlySuccessful } =
        useForm({
            title: '',
            content: '',
            category_id: preselectedCategory
                ? parseInt(preselectedCategory)
                : '',
        });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(DiscussionController.store().url);
    };

    const getCategoryLabel = (categoryId: number | string): string => {
        const category = categories.find((c) => c.id === categoryId);
        return category ? category.name : 'Select a category';
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Discussion" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="rounded-xl border border-sidebar-border/70 bg-white p-8 dark:border-sidebar-border dark:bg-gray-800">
                    <div className="mb-8">
                        <h1 className="mb-2 text-3xl font-bold text-gray-900 dark:text-white">
                            Create Discussion
                        </h1>
                        <p className="text-gray-600 dark:text-gray-300">
                            Start a new discussion in one of our forum
                            categories.
                        </p>
                    </div>

                    {recentlySuccessful && (
                        <div className="mb-6 rounded-md border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-900/50">
                            <p className="text-sm text-green-800 dark:text-green-200">
                                Discussion created successfully!
                            </p>
                        </div>
                    )}

                    <form onSubmit={handleSubmit} className="space-y-6">
                        <div>
                            <label
                                htmlFor="title"
                                className="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Title
                            </label>
                            <input
                                id="title"
                                type="text"
                                value={data.title}
                                onChange={(e) =>
                                    setData('title', e.target.value)
                                }
                                className="focus:ring-opacity-50 w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                placeholder="Enter discussion title..."
                                maxLength={255}
                                required
                            />
                            {errors.title && (
                                <p className="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {errors.title}
                                </p>
                            )}
                        </div>

                        <div>
                            <label
                                htmlFor="category_id"
                                className="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Category
                            </label>
                            <select
                                id="category_id"
                                value={data.category_id}
                                onChange={(e) =>
                                    setData('category_id', e.target.value)
                                }
                                className="focus:ring-opacity-50 w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">Select a category</option>
                                {categories.map((category) => (
                                    <option
                                        key={category.id}
                                        value={category.id}
                                    >
                                        {category.name}
                                    </option>
                                ))}
                            </select>
                            {errors.category_id && (
                                <p className="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {errors.category_id}
                                </p>
                            )}
                        </div>

                        <div>
                            <label
                                htmlFor="content"
                                className="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Content
                            </label>
                            <textarea
                                id="content"
                                value={data.content}
                                onChange={(e) =>
                                    setData('content', e.target.value)
                                }
                                rows={8}
                                className="focus:ring-opacity-50 w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                placeholder="Write your discussion content..."
                                required
                            />
                            {errors.content && (
                                <p className="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {errors.content}
                                </p>
                            )}
                        </div>

                        <div className="flex justify-end gap-4">
                            <Link
                                href={CategoryController.index().url}
                                className="focus:ring-opacity-50 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                disabled={processing}
                                className="focus:ring-opacity-50 rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                {processing
                                    ? 'Creating...'
                                    : 'Create Discussion'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
