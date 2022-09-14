curl -d '{"aps":{"alert":"Hey buddy","sound":"default"}}' --cert "ZimdelDevPush.pem":"" -H "apns-topic: [BUNDLE IDENTIFIER]" --http2 https://api.development.push.apple.com/3/device/[TOKEN]


curl -d '{"aps":{"alert":"Hi!","sound":"default"}}' --cert "ZimdelDevPush.pem":"" -H "apns-topic: vocolboy.samplepush" --http2  https://api.development.push.apple.com/3/device/20318f6d221122e0a941942728052b7aaf370d0b22bfb56e315f193b056bd67d



~/bin/curl -v \
-d '{"aps":{"alert":"Hi!","badge":42}}' \
-H "apns-topic: com.zimdle.zimdle" \
-H "apns-priority: 10" \
--http2 \
--cert ZimdelDevPush.pem \
https://api.development.push.apple.com/3/device/20318f6d221122e0a941942728052b7aaf370d0b22bfb56e315f193b056bd67d

~/bin/curl -v \
-d '{"aps":{"alert":"Hi nilya hailu ke nai","badge":42}}' \
-H "apns-priority: 10" \
--http2 \
--cert ZimdelDevPush.pem \
https://api.development.push.apple.com/3/device/20318f6d221122e0a941942728052b7aaf370d0b22bfb56e315f193b056bd67d


~/bin/curl --version