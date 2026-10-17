import MarkdownIt from 'markdown-it';

const markdown = new MarkdownIt({ html: true, linkify: true });

/**
 * Render trusted Markdown (e.g. admin-authored content) to HTML.
 */
export function renderMarkdown(content: string): string {
    return markdown.render(content);
}
