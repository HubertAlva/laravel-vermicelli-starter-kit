declare namespace App.Data {
export type FormPostData = {
name?: string;
excerpt?: string;
content?: string;
thumbnail: any | string | null;
published_at: boolean;
is_new_thumbnail: boolean;
deleted_at: string | null;
tags?: Array<any>;
};
export type PaginatorLinkData = {
url?: string;
label: string;
active: boolean;
};
export type PaginatorMetaData = {
current_page: number;
first_page_url: string;
from: number;
last_page: number;
last_page_url: string;
next_page_url?: string;
path: string;
per_page: number;
prev_page_url?: string;
to: number;
total: number;
};
export type PostData = {
id: number;
name: string;
slug: string;
excerpt: string;
content: string;
thumbnail?: string;
published_at?: string;
created_at?: string;
updated_at?: string;
deleted_at: string | null;
tags: Array<App.Data.TagData> | null;
};
export type TagData = {
id: number;
name: string;
slug: string;
};
}
