import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import CategoryController from '@/actions/App/Http/Controllers/CategoryController';
import type { BreadcrumbItem } from '@/types';

interface User {
    id: number;
    name: string;
}

interface Discussion {
    id: number;
    title: string;
    slug: string;
    replies_count: number;
    is_pinned: boolean;
    created_at: string;
    last_reply_at: string | null;
    user: User;
}

interface Category {
    id: number;
    name: string;
    slug: string;
    description: string | null;
}

interface CategoryDetailPageProps {
    category: Category;
    discussions: {
        data: Discussion[];
        links: {
            first: string;
            last: string;
            prev: string | null;
            next: string | null;
        };
        meta: {
            current_page: number;
            from: number;
            last_page: number;
            per_page: number;
            to: number;
            total: number;
        };
    };
}

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const getReplyText = (count: number): string => {
    return count === 1 ? 'reply' : 'replies';
};

export default function Show({
    category,
    discussions,
}: CategoryDetailPageProps) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Categories',
            href: CategoryController.index().url,
        },
        {
            title: category.name,
            href: CategoryController.show({ category: category.slug }).url,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={category.name} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="rounded-xl border border-sidebar-border/70 bg-white p-8 dark:border-sidebar-border dark:bg-gray-800">
                    <div className="mb-8">
                        <h1 className="mb-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {category.name}
                        </h1>
                        {category.description && (
                            <p className="text-gray-600 dark:text-gray-300">
                                {category.description}
                            </p>
                        )}
                    </div>

                    {discussions.data.length === 0 ? (
                        <div className="py-12 text-center">
                            <p className="text-gray-600 dark:text-gray-300">
                                No discussions in this category yet.
                            </p>
                            <p className="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Be the first to start a discussion!
                            </p>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            {discussions.data.map((discussion) => (
                                <div
                                    key={discussion.id}
                                    className="rounded-lg border border-gray-200 p-6 transition-colors duration-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700"
                                >
                                    <div className="flex items-start justify-between">
                                        <div className="flex-1">
                                            <div className="mb-2 flex items-center gap-2">
                                                {discussion.is_pinned && (
                                                    <span className="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        Pinned
                                                    </span>
                                                )}
                                                <Link
                                                    href={`/discussions/${discussion.slug}`}
                                                    className="text-lg font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                                >
                                                    {discussion.title}
                                                </Link>
                                            </div>
                                            <div className="text-sm text-gray-600 dark:text-gray-400">
                                                by {discussion.user.name} ·{' '}
                                                {formatDate(
                                                    discussion.created_at,
                                                )}
                                                {discussion.last_reply_at && (
                                                    <>
                                                        {' '}
                                                        · Last reply{' '}
                                                        {formatDate(
                                                            discussion.last_reply_at,
                                                        )}
                                                    </>
                                                )}
                                            </div>
                                        </div>
                                        <div className="text-right">
                                            <div className="text-sm font-medium text-gray-900 dark:text-white">
                                                {discussion.replies_count}
                                            </div>
                                            <div className="text-xs text-gray-500 dark:text-gray-400">
                                                {getReplyText(
                                                    discussion.replies_count,
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}

                            {/* Pagination */}
                            {discussions.meta.last_page > 1 && (
                                <div className="mt-8 flex items-center justify-center gap-2">
                                    {discussions.links.prev && (
                                        <Link
                                            href={discussions.links.prev}
                                            className="rounded-md border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700"
                                        >
                                            Previous
                                        </Link>
                                    )}

                                    <span className="text-sm text-gray-600 dark:text-gray-400">
                                        Page {discussions.meta.current_page} of{' '}
                                        {discussions.meta.last_page}
                                    </span>

                                    {discussions.links.next && (
                                        <Link
                                            href={discussions.links.next}
                                            className="rounded-md border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700"
                                        >
                                            Next
                                        </Link>
                                    )}
                                </div>
                            )}
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
