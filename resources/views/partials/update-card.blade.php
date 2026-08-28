<a href="{{ route('updates.show', $update->slug) }}" class="update-card">
    <div class="thumb" style="background-image:url('{{ $update->cover_image_url ?: 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1000&q=80' }}')"></div>
    <div class="body">
        <div class="meta">
            <span class="badge">{{ optional($update->published_at)->format('d M Y') ?: $update->created_at->format('d M Y') }}</span>
        </div>
        <h3>{{ $update->title }}</h3>
        <p>{{ \Illuminate\Support\Str::limit($update->excerpt ?: strip_tags($update->body), 120) }}</p>
    </div>
</a>
