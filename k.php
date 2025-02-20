   <!-- Bagian Komentar -->
   <div class="col-md-4">
                        <h5><?php echo htmlspecialchars($ardi_data['judulfoto']); ?></h5>
                        <p><strong>Tanggal:</strong> <?php echo htmlspecialchars($ardi_data['tanggalupload']); ?></p>
                        <p><?php echo htmlspecialchars($ardi_data['deskripsifoto']); ?></p>
                        <hr>
                        <h5>Komentar:</h5>

                        <div id="commentSection">
                            <?php
                            $ardi_komen = mysqli_query($ardi_conn, "SELECT komenfoto.*, user.namalengkap FROM komenfoto INNER JOIN user ON komenfoto.userid = user.userid WHERE komenfoto.fotoid='$fotoid' ORDER BY komenfoto.tanggalkomen DESC");
                            while ($row = mysqli_fetch_array($ardi_komen)) {
                                $timeAgo = strtotime($row['tanggalkomen']); // Konversi ke timestamp
                                echo '<p><strong>' . htmlspecialchars($row['namalengkap']) . ':</strong> ' . htmlspecialchars($row['isikomen']) . '<br>';
                                echo '<small class="time-ago" data-time="' . $timeAgo . '"></small>';

                                // Tombol hapus hanya muncul untuk pemilik komentar
                                if ($row['userid'] == $ardi_userid) {
                                    echo ' <a href="?delete_comment_id=' . $row['komenid'] . '" class="text-danger">Hapus</a>';
                                }
                                echo '</p>';
                            }
                            ?>
                        </div>

                        <form action="ardi_proses_komen.php" method="POST">
                            <div class="input-group">
                                <input type="hidden" name="fotoid" value="<?php echo $fotoid; ?>">
                                <input type="text" name="isikomen" class="form-control" placeholder="Tambah Komentar" required>
                                <button type="submit" name="kirimkomentar" class="btn btn-outline-primary">Kirim</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Fungsi untuk mengupdate waktu relatif secara otomatis
function updateTimeAgo() {
    let elements = document.querySelectorAll('.time-ago');
    let now = Math.floor(Date.now() / 1000);

    elements.forEach(el => {
        let commentTime = el.getAttribute('data-time');
        let timeDiff = now - commentTime;
        let timeAgoText = getTimeAgoText(timeDiff);
        el.textContent = timeAgoText;
    });
}

// Fungsi untuk mengubah selisih waktu menjadi teks relatif
function getTimeAgoText(seconds) {
    if (seconds < 60) return `${seconds} detik yang lalu`;
    let minutes = Math.floor(seconds / 60);
    if (minutes < 60) return `${minutes} menit yang lalu`;
    let hours = Math.floor(minutes / 60);
    if (hours < 24) return `${hours} jam yang lalu`;
    let days = Math.floor(hours / 24);
    if (days < 7) return `${days} hari yang lalu`;
    let weeks = Math.floor(days / 7);
    if (weeks < 4) return `${weeks} minggu yang lalu`;
    let months = Math.floor(days / 30);
    if (months < 12) return `${months} bulan yang lalu`;
    let years = Math.floor(days / 365);
    return `${years} tahun yang lalu`;
}

// Jalankan update waktu setiap 1 detik
setInterval(updateTimeAgo, 1000);
updateTimeAgo();
</script>