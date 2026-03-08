declare namespace App.Data {
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
export type PostFormData = {
name?: string;
excerpt?: string;
content?: string;
thumbnail: any | string | null;
published_at: boolean;
is_new_thumbnail: boolean;
deleted_at: string | null;
tags: Array<string> | null;
};
export type SendContactData = {
name: string;
email: string;
subject?: string;
message: string;
};
export type TagData = {
id: number;
name: string;
slug: string;
};
export type TestFormData = {
text?: string;
textarea?: string;
taglistbox: Array<string | number> | null;
tags: Array<string> | null;
switch: boolean;
select: string | number | null;
radio: string | number | null;
phone?: string;
password?: string;
number?: number;
markdown?: string;
image: any | string | null;
is_new_image: boolean;
file: any | string | null;
combobox: string | number | null;
checkbox: boolean;
date: any;
datetime: any;
};
export type UserData = {
id: number;
name: string;
email: string;
email_verified_at?: string;
role?: string;
created_at?: string;
updated_at?: string;
};
export type UserFormData = {
name?: string;
email?: string;
role: string | null;
password: string | null;
password_confirmation: string | null;
};
}
