declare namespace App.Data {
export type PostData = {
id: number;
name: string;
slug: string;
excerpt: string;
content: string;
thumbnail?: string;
additional_thumbnail?: string;
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
